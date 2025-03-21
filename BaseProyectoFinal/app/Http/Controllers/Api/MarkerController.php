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
        /**
         USE whatevernever;

SELECT 
    m.user_id, 
    MAX(m.id) AS id, 
    MAX(m.name) AS name, 
    MAX(m.created_at) AS created_at
FROM markers AS m
INNER JOIN friends AS f ON f.sender_user_id = m.user_id OR f.reciver_user_id = m.user_id
WHERE f.request_status = 1
GROUP BY m.user_id;

         */
        $user_id = $request->input("user_id");

        if ($user_id == null) {
            return response()->json(["status" => 500, "message" => "User Id invalid"]);
        }

        $markers = DB::table('markers as m')
            ->join('friends as f', function ($join) {
                $join->on('m.user_id', '=', 'f.sender_user_id')
                    ->orOn('m.user_id', '=', 'f.reciver_user_id');
            })
            ->where('f.request_status', 0)
            ->where(function ($query) use ($user_id) {
                $query->where('f.sender_user_id', $user_id)
                    ->orWhere('f.reciver_user_id', $user_id);
            })
            ->whereIn('m.user_id', function ($query) use ($user_id) {
                $query->select('user_id')
                    ->from('markers')
                    ->whereIn('user_id', function ($subQuery) use ($user_id) {
                        $subQuery->select('sender_user_id')
                            ->orWhere('reciver_user_id', $user_id);
                    })
                    ->orderBy('created_at', 'desc')
                    ->groupBy('user_id');
            })
            ->select('m.*')
            ->get();

        return response()->json(["status" => 200, "markers" => $markers]);
    }


}
