<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use Illuminate\Http\Request;
use App\Models\FriendGroup;
use App\Models\FriendGroupFriends;

class FriendGroupsController extends Controller
{
    //Creates a group of friends
    public function createGroup(Request $request) {
        $request->validate([
            'name' => 'required|string',
        ]);

        if(FriendGroup::where('name', $request->name)->exists()) {
            return response()->json(['message' => 'Group with the same name already exists', 'type' => 'bad'], 200);
        }
        
        FriendGroup::create([
            'name' => $request->name,
            'owner_user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Group created', 'type' => 'good'], 201);

    }

    public function dropGroup(Request $request) {
        $request->validate([
            'id_group' => 'required|int',
        ]);

        $group = FriendGroup::find($request->id_group);

        if (!$group) {
            return response()->json(['message' => 'This Group doesn\'t exist', 'type' => 'bad']);
        }

        $group->delete();

        return response()->json(['message' => 'Group deleted', 'type' => 'good']);
    }

    //Show auth user groups
    public function showMyGroups(Request $request) {

        $result = FriendGroup::where("owner_user_id", auth()->id())->get();

        return response()->json(
            $result
        );
    }

    public function showJoinedGroups(Request $request) {
        $request = "Not working right now";

        return response()->json(
            $request
        );
    }

    //Añadir un amigo a un grupo
    public function addToGroup(Request $request) {
        $request->validate([
            'id_group' => 'required|int',
            'id_target_user' => 'required|exists:users,id',
        ]);

        $group = FriendGroup::where('id', $request->id_group)->first();

        if (!$group) {
            return response()->json(['message' => 'This group doesen\'t exist'], 200);
        }

        if (auth()->id() != $group->owner_user_id) {
            return response()->json(['message' => 'You are not the owner of this group'], 401);
        }

        FriendGroupFriends::create([
            'friends_id' => $request->id_target_user,
            'friend_group_id' => $request->id_target_user,
        ]);

        return response()->json(['message' => 'User added to the group'], 200);
    }
}
