<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friend;
use App\Http\Controllers\Controller;

class FriendController extends Controller
{
    public function showFriends(Request $request) {
        $search = $request->query('search');

        $query = User::query();

        if ($search) 
        {
            $query->where('name', 'like', "%$search%");
        }

        $friends = $query->orderBy('name', 'asc')->get(['id', 'name', 'username', 'last_lng', 'last_lat']);

        foreach ($friends as $friend) {
            $friend->getFirstMedia('users');
        }

        return response()->json($friends);
    }

    /*
    public function showMyFriends(Request $request) {
        $userId = $request->query('user');
        $status = $request->query('status');

        $user = User::findOrFail($userId);
    
        // Obtener las solicitudes de amistad enviadas por el usuario
        $sentRequests = $user->sentFriendRequests()
            ->where('request_status', $status)  // Si quieres filtrar por estado, como "pendiente"
            ->get();
    
        return response()->json($sentRequests);
    }
    */

    //Show user friends
    public function showMyFriends(Request $request)
    {
        $userId = $request->query('user');
        //$status = $request->query('status');

        $user = User::findOrFail($userId); // Obtener usuario autenticado

        $friendRequests = $user->friendsReceived()->get();

        return response()->json(
            $friendRequests
        );
    }

    public function requestsSent(Request $request) {
        $userId = $request->query('user');

        $user = User::findOrFail($userId);
        
        $friendRequests = $user->friendsSent()->get();

        return response()->json(
            $friendRequests
        );
    }

    //Delete Friendship or friend request (its the same in the bbdd)
    public function deleteFriend(Request $request) {
        $request->validate([
            'friend_id' => 'required|exists:friends,id',
        ]);
    
        $user = auth()->user();
    
        $friend = Friend::where(function ($query) use ($user) {
            $query->where('sender_user_id', $user->id)
                  ->orWhere('reciver_user_id', $user->id);
        })->findOrFail($request->friend_id);
    
        $friend->delete();
    
        return response()->json([
            'message' => 'Friend deleted successfully',
        ], 200);
    }
    

    //Accept friend request
    public function acceptFriend(Request $request) {
        $friendship_id = $request->id;
    
        // Buscar la fila en la tabla friends
        $friendship = Friend::find($friendship_id);
    
        if (!$friendship) {
            return response()->json(['error' => 'Friendship not found'], 404);
        }
    
        // Modificar el campo request_status
        $friendship->request_status = 1;
        $friendship->save();
    
        return response()->json([
            'message' => 'Friend request accepted successfully',
            'friendship' => $friendship
        ]);
    }  
    
    //Create Friend Request
    public function createRequest(Request $request) {
        $request->validate([
            'id_sender' => 'required|exists:users,id',
            'id_receiver' => 'required|exists:users,id|different:id_sender',
        ]);
    
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
            return response()->json(['error' => 'Friend request already exists'], 400);
        }
    
        $friendship = Friend::create([
            'sender_user_id' => $request->id_sender,
            'reciver_user_id' => $request->id_receiver,
            'request_status' => 0, // 0 = pending
        ]);
    
        return response()->json([
            'message' => 'Friend request sent successfully',
            'friendship' => $friendship
        ], 201);
    }    
}
