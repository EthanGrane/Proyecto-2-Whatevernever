<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function showFriends() {
        $compe = [
            [ "name"=> "Rana Gustavo", "username"=> "ranagustavo", "image"=> "/images/users/ranagustavo.webp" ],
            [ "name"=> "Juan Pérez", "username"=> "juanperez", "image"=> "/images/users/ranagustavo.webp" ],
            [ "name"=> "Maria López", "username"=> "marialopez", "image"=> "/images/users/ranagustavo.webp" ],
            [ "name"=> "Rana Gustavo", "username"=> "ranagustavo", "image"=> "/images/users/ranagustavo.webp" ],
            [ "name"=> "Juan Pérez", "username"=> "juanperez", "image"=> "/images/users/ranagustavo.webp" ],
            [ "name"=> "Maria López", "username"=> "marialopez", "image"=> "/images/users/ranagustavo.webp" ],
            [ "name"=> "Rana Gustavo", "username"=> "ranagustavo", "image"=> "/images/users/ranagustavo.webp" ],
            [ "name"=> "Juan Pérez", "username"=> "juanperez", "image"=> "/images/users/ranagustavo.webp" ],
            [ "name"=> "Maria López", "username"=> "marialopez", "image"=> "/images/users/ranagustavo.webp" ]
        ];

        return $compe;
    }
}
