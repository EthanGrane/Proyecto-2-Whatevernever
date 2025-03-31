<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarkerList;

class MarkerListController extends Controller
{
    public function index(Request $request) {
        try {
            $user = auth()->id();

            $lists = MarkerList::where('owner_user_id', $user)->get();

            return response()->json($lists, 200);
            
        } catch (\Throwable $th) {
            return response()->json(["Error" => $th->getMessage()], 500);

        }
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                "name" => "required|string",
            ]);

            $user = auth()->id();

            if (!$user) {
                return response()->json(['message' => 'User no authenticated'], 403);
            }

            $exists = MarkerList::where('name', $request->name)->exists();

            if ($exists) {
                return response()->json(['message' => 'This List already exists'], 400);
            }

            $marker = MarkerList::create([
                'name' => $request->name,
                'owner_user_id' => $user
            ]);

            return response()->json(["message" => "marker list created", "MarkerList" => $marker], 201);

        } catch (\Exception $e) {
            return response()->json(["Error" => $e->getMessage()], 500);

        }
    }

    public function destroy(Request $request) {
        try {
            $request->validate([
                "id" => "required|int"
            ]);

            $list = MarkerList::where("id", $request->id)->where("owner_user_id", auth()->id())->first();

            if (!$list) {
                return response()->json(['message' => 'Group not found or you are not the owner'], 404);
            }

            $list->delete();
            
            return response()->json(['message' => "Marker list deleted"], 200);

        } catch (\Throwable $th) {
            //throw $th;

            return response()->json(["Error" => $th->getMessage()], 500);
        }
    }
}
