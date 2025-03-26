<?php

namespace App\Http\Controllers\Api;

use App\Models\Friend;
use App\Models\Marker;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class MarkerController extends Controller
{
    public function getLastMarkerFromFriends(Request $request)
    {
        $user = auth()->user();

        if ($user == null) {
            return response()->json(["status" => 500, "message" => "User Id invalid"]);
        }

        $markers = DB::table("markers as m")
            ->join("friends as f", function ($join) {
                $join->on("m.user_id", "=", "f.reciver_user_id");
            })
            ->where("f.request_status", 1)
            ->where("f.sender_user_id", $user->getAuthIdentifier())
            ->join(
                DB::raw("(SELECT user_id, MAX(id) as last_id FROM markers GROUP BY user_id) as latest_markers"),
                'm.id',
                '=',
                'latest_markers.last_id'
            )
            ->select("m.user_id", "m.id", "m.lat", "m.lng", "m.name", "m.description")
            ->get();

        return response()->json(["status" => 200, "markers" => $markers]);
    }
    public function getAllMarkersFromFriendId(Request $request)
    {
        try 
        {
            $request->validate([
                'friend_id' => 'required|int',
            ]);

            $user = auth()->user();

            $status = Friend::where('sender_user_id', $user->id)
                ->where('reciver_user_id', $request->friend_id)
                ->first();

            if ($status) 
            {
                $markers = Marker::where("user_id", $request->friend_id)->first();
            } 
            else 
            {
                $markers = null;
            }
        } 
        catch (ModelNotFoundException $e) 
        {
            print($e->getMessage());
        }
        return response()->json(["status" => 200, "markers" => $markers]);
    }

}
