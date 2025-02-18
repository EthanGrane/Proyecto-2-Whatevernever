<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FriendController extends Controller
{
    public function showFriends(Request $request) {
        $search = $request->query('search');

        $query = User::query();

        if ($search) 
        {
            $query->where('name', 'like', "%$search%");
        }

        $friends = $query->get(['name', 'username', 'last_lng', 'last_lat']);

        return response()->json($friends);
    }
}
