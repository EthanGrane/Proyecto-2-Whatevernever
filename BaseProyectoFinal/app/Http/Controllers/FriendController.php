<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FriendController extends Controller
{
    public function showFriends(Request $request) {
        $search = $request->query('search');

        $query = User::query();

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $friends = $query->orderBy('name', 'asc')->get(['id', 'name', 'username']);

        foreach ($friends as $friend) {
            $media = $friend->getFirstMedia('users');
            $friend->image = $media ? $media->getUrl() : asset('images/ProfilePicture_7.jpg');
        }

        return response()->json($friends);
    }
}
