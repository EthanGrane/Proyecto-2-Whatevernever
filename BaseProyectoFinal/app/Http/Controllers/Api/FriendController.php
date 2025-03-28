<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Friend;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FriendController extends Controller
{
    /*
     * Show a list of all friends (Index)
     */
    public function index()
    {
        try {
            $friends = Friend::all(); // O puedes usar tu lógica personalizada para obtener amigos
            return response()->json($friends, 200);
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "Error" => $e->getMessage()]);
        }
    }

    /*
     * Store a new friend request (Create)
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_sender' => 'required|exists:users,id',
            'id_receiver' => 'required|exists:users,id',
        ]);

        if ($request->id_sender != auth()->id()) {
            return response()->json(['message' => 'Error you are not authorized to do this'], 401);
        }

        if ($request->id_sender == $request->id_receiver) {
            return response()->json(['message' => 'You can\'t be your own friend D:>', 'type' => 'bad'], 200);
        }

        $existingFriendship = Friend::where(function ($query) use ($request) {
            $query->where('sender_user_id', $request->id_sender)
                ->where('reciver_user_id', $request->id_receiver);
        })
            ->orWhere(function ($query) use ($request) {
                $query->where('sender_user_id', $request->id_receiver)
                    ->where('reciver_user_id', $request->id_sender);
            })
            ->first();

        if ($existingFriendship) {
            return response()->json(['message' => 'Already sent, don\'t be annoying >:|', 'type' => 'bad'], 200);
        }

        $friendship = Friend::create([
            'sender_user_id' => $request->id_sender,
            'reciver_user_id' => $request->id_receiver,
            'request_status' => 0, // 0 = pending
        ]);

        return response()->json([
            'message' => 'Friend request sent :D',
            'type' => 'good',
            'friendship' => $friendship
        ], 201);
    }

    /*
     * Show a specific friend (Show)
     */
    public function show($id)
    {
        try {
            $friendship = Friend::findOrFail($id);
            return response()->json($friendship, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => 404, "Error" => "Friendship not found"]);
        }
    }

    /*
     * Update a friend request status (Update)
     */
    public function update(Request $request, $id)
    {
        try {
            $friendship = Friend::findOrFail($id);
            $friendship->update($request->all());
            return response()->json(["status" => 200, "friendship" => $friendship]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => 404, "Error" => "Friendship not found"]);
        }
    }

    /*
     * Delete a friend or friend request (Destroy)
     */
    public function destroy($id)
    {
        try {
            $friendship = Friend::findOrFail($id);
            $friendship->delete();
            return response()->json(["status" => 200, "message" => "Friendship deleted successfully"]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => 404, "Error" => "Friendship not found"]);
        }
    }

    // Funciones personalizadas
    public function showFriends(Request $request)
    {
        $search = $request->query('search');
        $query = User::query();

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $friends = $query->orderBy('name', 'asc')->get(['id', 'name', 'username', 'last_lng', 'last_lat']);

        foreach ($friends as $friend) {
            $friend->getFirstMedia('users');
        }

        return response()->json($friends);
    }

    public function showMyFriends(Request $request)
    {
        $userId = auth()->user()->id;
        $user = User::findOrFail($userId);
        $friendRequests = $user->friendsReceived()->where('request_status', '!=', 1)->get();

        return response()->json($friendRequests);
    }

    public function requestsSent(Request $request)
    {
        $userId = $request->query('user');
        $user = User::findOrFail($userId);
        $friendRequests = $user->friendsSent()->where('request_status', '!=', 1)->get();

        return response()->json($friendRequests);
    }

    public function ShowAllFriends(Request $request)
    {
        $userId = $request->query('user');
        $user = User::findOrFail($userId);

        $friendsSent = $user->friendsSent()->where('request_status', '!=', 0)->get();
        $friendsReceived = $user->friendsReceived()->where('request_status', '!=', 0)->get();

        $allFriends = $friendsSent->merge($friendsReceived);

        $formattedFriends = $allFriends->map(function ($friend) {
            return [
                'id' => $friend->id,
                'request_status' => $friend->request_status,
                'user' => $friend->sender_user_id == auth()->id() ? $friend->reciver : $friend->sender,
                'created_at' => $friend->created_at,
                'updated_at' => $friend->updated_at,
            ];
        });

        return response()->json($formattedFriends);
    }

    public function deleteFriend(Request $request)
    {
        $request->validate(['friend_id' => 'required|exists:friends,id']);
        $user = auth()->user();

        $friend = Friend::where(function ($query) use ($user) {
            $query->where('sender_user_id', $user->id)
                ->orWhere('reciver_user_id', $user->id);
        })->findOrFail($request->friend_id);

        $friend->delete();

        return response()->json(['message' => 'Friend deleted successfully'], 200);
    }

    public function acceptFriend(Request $request)
    {
        $friendship_id = $request->id;
        $friendship = Friend::find($friendship_id);

        if (!$friendship) {
            return response()->json(['error' => 'Friendship not found'], 404);
        }

        $friendship->request_status = 1;
        $friendship->save();

        return response()->json([
            'message' => 'Friend request accepted successfully',
            'friendship' => $friendship
        ]);
    }
}
