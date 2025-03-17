<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FriendGroup;

class FriendGroupsController extends Controller
{
    //Creates a group of friends
    public function createGroup(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'id_owner' => 'required|exists:users,id',
        ]);

        if (auth()->id() != $request->id_owner) {
            return response()->json(['message' => 'Error you are not authorized to do this'], 401);
        }

        if(FriendGroup::where('name', $request->name)->exists()) {
            return response()->json(['message' => 'Group with the same name already exists'], 200);
        }
        
        FriendGroup::create([
            'name' => $request->name,
            'owner_user_id' => $request->id_owner,
        ]);

        return response()->json(['message' => 'group created'], 201);

    }

    public function addToGroup(Request $request) {
        $request->validate([
            'id_owner' => 'required|exists:users,id',
            'id_group' => 'required|int',
            'id_target_user' => 'required|exists:users,id',
        ]);

        if (auth()->id() != $request->id_owner) {
            return response()->json(['message' => 'You are not the owner of this group'], 401);
        }

        if (!FriendGroup::where('id', $request->id_group)->exists()) {
            return response()->json(['message' => 'This group doesen\'t exist'], 200);
        }

        return response()->json(['message' => 'User added to the group'], 200);
    }
}
