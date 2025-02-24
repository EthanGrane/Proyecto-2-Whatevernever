<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
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

    public function showMyFriends(Request $request) {
        $userId = $request->query('user');

        $user = User::findOrFail($userId);
    
        // Obtener las solicitudes de amistad enviadas por el usuario
        $sentRequests = $user->sentFriendRequests()
            ->where('request_status', 1)  // Si quieres filtrar por estado, como "pendiente"
            ->get();
    
        return response()->json($sentRequests);
    }
}
