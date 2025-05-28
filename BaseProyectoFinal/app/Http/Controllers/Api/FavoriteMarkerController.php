<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marker;
use App\Models\user;

class FavoriteMarkerController extends Controller
{

    public function addFavorite(Request $request, Marker $marker)
    {
        $user = $request->user();

        $user->favoriteMarkers()->attach($marker->id);

        return response()->json(['message' => 'Marcador añadido a favoritos']);
    }


    public function removeFavorite(Request $request, Marker $marker)
    {
        $user = $request->user();

        // Quitar de favoritos
        $user->favoriteMarkers()->detach($marker->id);

        return response()->json(['message' => 'Marcador eliminado de favoritos']);
    }


    public function getFavorite(Request $request)
    {
        $user = $request->user();
        $favorites = $user->favoriteMarkers()->get();

        return response()->json([
            'favorites' => $favorites
        ]);
    }
}
