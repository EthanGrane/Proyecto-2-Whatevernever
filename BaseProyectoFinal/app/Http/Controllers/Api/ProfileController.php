<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function update(UpdateProfileRequest $request)
    {
        $profile = Auth::user();
        $profile->name = $request->name;
        $profile->email = $request->email;

        if ($profile->save()) {
            return $this->successResponse($profile, 'User updated');;
        }
        return response()->json(['status' => 403, 'success' => false]);
    }

    public function user(Request $request)
    {
        $user = $request->user()->load('roles');
        $avatar = '';
        if (count($user->media) > 0) {
            $avatar = $user->media[0]->original_url;
        }
        $user->avatar = $avatar;


        return $this->successResponse($user, 'User found');
    }

    public function getProfileByUsername($username)
    {
        // Buscar el usuario por su nombre de usuario
        $user = User::where('username', $username)->first();
    
        // Si no se encuentra el usuario, retornar un error
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    
        // Si el usuario es encontrado, devolver la información
        $avatar = '';
        if ($user->media->isNotEmpty()) {
            $avatar = $user->media->first()->original_url;
        }
    
        $user->avatar = $avatar;
    
        return response()->json([
            'success' => true,
            'message' => 'User found',
            'data' => $user
        ]);
    }

}
