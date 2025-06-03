<?php

namespace App\Http\Controllers\Api;

use App\Models\Friend;
use App\Models\Marker;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MarkerController extends Controller
{
    public function getLastMarkerFromFriends(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(["status" => 401, "message" => "Usuario no autenticado"]);
        }

        $friendIds = Friend::where(function ($query) use ($user) {
            $query->where('sender_user_id', $user->id)
                ->orWhere('reciver_user_id', $user->id);
        })
            ->where('request_status', 1)
            ->get()
            ->map(function ($friend) use ($user) {
                return $friend->sender_user_id == $user->id
                    ? $friend->reciver_user_id
                    : $friend->sender_user_id;
            })
            ->toArray();

        if (empty($friendIds)) {
            return response()->json(["status" => 200, "markers" => []]);
        }

        $latestMarkers = Marker::whereIn('user_id', $friendIds)
            ->select('user_id', DB::raw('MAX(id) as max_id'))
            ->groupBy('user_id')
            ->pluck('max_id')
            ->toArray();

        $markers = Marker::whereIn('id', $latestMarkers)->get();

        return response()->json(["status" => 200, "markers" => $markers]);
    }

    public function getAllMarkersFromUserId(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|int',
            ]);

            $authUser = auth()->user();
            $user_id = $request->user_id;

            if ($authUser->id != $user_id) {
                // Comprobar si hay amistad en cualquier dirección
                $status = Friend::where(function ($query) use ($authUser, $user_id) {
                    $query->where('sender_user_id', $authUser->id)
                        ->where('reciver_user_id', $user_id);
                })
                    ->orWhere(function ($query) use ($authUser, $user_id) {
                        $query->where('sender_user_id', $user_id)
                            ->where('reciver_user_id', $authUser->id);
                    })
                    ->first();

                // Si no son amigos, no se devuelven marcadores
                if (!$status) {
                    return response()->json([
                        'status' => 403,
                        'message' => 'No tienes permiso para ver los marcadores de este usuario.'
                    ]);
                }
            }

            // Si son amigos o es el propio usuario autenticado
            $markers = Marker::where("user_id", $user_id)->get();

            if ($markers->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No se encontraron marcadores para este usuario.'
                ]);
            }

            return response()->json([
                "status" => 200,
                "markers" => $markers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Hubo un problema al procesar tu solicitud.',
                'error' => $e->getMessage()
            ]);
        }
    }


    /*
     * CRUD
     */
    public function index()
    {
        try {
            $marker = Marker::all();
            return response()->json($marker, 200);
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "Error" => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $marker = Marker::findOrFail($id);
            return response()->json($marker);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => 404, "Error" => "Marker not found"]);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                "name" => "required|string",
                "description" => "required|string",
                "lng" => "required|numeric",
                "lat" => "required|numeric",
                "zoom" => "nullable|numeric",
                "bearing" => "nullable|numeric",
                "pitch" => "nullable|numeric",
                "user_id" => "required|integer"
            ]);

            $marker = Marker::create($request->all());
            return response()->json(["status" => 201, "marker" => $marker]);
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "Error" => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $marker = Marker::findOrFail($id);
            $request->validate([
                "name" => "sometimes|string",
                "description" => "sometimes|string",
                "lng" => "sometimes|numeric",
                "lat" => "sometimes|numeric",
                "zoom" => "sometimes|numeric",
                "bearing" => "sometimes|numeric",
                "pitch" => "sometimes|numeric",
                "user_id" => "sometimes|integer"
            ]);

            $marker->update($request->all());
            return response()->json(["status" => 200, "marker" => $marker]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => 404, "Error" => "Marker not found"]);
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "Error" => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $marker = Marker::findOrFail($id);
            $marker->delete();
            return response()->json(["status" => 200, "message" => "Marker deleted successfully"]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => 404, "Error" => "Marker not found"]);
        }
    }
}
