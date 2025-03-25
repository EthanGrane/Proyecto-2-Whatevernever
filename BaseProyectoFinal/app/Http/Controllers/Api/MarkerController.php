<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\Marker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class MarkerController extends Controller
{
    public function getLastMarkerFromFriends(Request $request)
    {
        $user_id = 103; //$request->input("user_id");

        if ($user_id == null) {
            return response()->json(["status" => 500, "message" => "User Id invalid"]);
        }

        $markers = DB::table("markers as m")
            ->join("friends as f", function ($join) {
                $join->on("m.user_id", "=", "f.reciver_user_id");
            })
            ->where("f.request_status", 1)
            ->where("f.sender_user_id", $user_id)
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

}
