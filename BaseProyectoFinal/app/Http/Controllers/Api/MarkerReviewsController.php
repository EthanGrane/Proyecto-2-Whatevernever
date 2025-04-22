<?php

namespace App\Http\Controllers\Api;

use App\Models\MarkerReviews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MarkerReviewsController extends Controller
{
    public function getAvgStarsByMarkerId($marker_id)
    {
        try {
            if (!$marker_id) {
                return response()->json([
                    'status' => 400,
                    'error' => 'marker_id is required'
                ], 400);
            }
    
            $average = MarkerReviews::where('marker_id', $marker_id)->avg('review_stars');
            $count = MarkerReviews::where('marker_id', $marker_id)->count();
    
            return response()->json([
                'status' => 200,
                'average_stars' => round($average ?? 0, 2),
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /*
     * CRUD
     */
    public function index()
    {
        try {
            $reviews = MarkerReviews::all();
            return response()->json($reviews, 200);
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "Error" => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $review = MarkerReviews::findOrFail($id);
            return response()->json($review);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => 404, "Error" => "Review not found"]);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                "review_stars" => "required|numeric|min:0|max:5",
                "review_content" => "required|string",
                "user_id" => "required|integer",
                "marker_id" => "required|integer"
            ]);

            $review = MarkerReviews::create($request->all());
            return response()->json(["status" => 201, "review" => $review]);
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "Error" => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $review = MarkerReviews::findOrFail($id);
            $request->validate([
                "review_stars" => "sometimes|numeric|min:0|max:5",
                "review_content" => "sometimes|string",
                "user_id" => "sometimes|integer",
                "marker_id" => "sometimes|integer"
            ]);

            $review->update($request->all());
            return response()->json(["status" => 200, "review" => $review]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => 404, "Error" => "Review not found"]);
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "Error" => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $review = MarkerReviews::findOrFail($id);
            $review->delete();
            return response()->json(["status" => 200, "message" => "Review deleted successfully"]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => 404, "Error" => "Review not found"]);
        }
    }
}
