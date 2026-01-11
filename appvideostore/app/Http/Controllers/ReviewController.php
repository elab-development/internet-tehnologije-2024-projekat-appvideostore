<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    
    public function index(Movie $movie): JsonResponse
    {
        $reviews = $movie->reviews()
            ->with('user')
            ->latest()
            ->paginate(15);

        return response()->json($reviews);
    }

   
    public function store(Request $request, Movie $movie): JsonResponse
    {
        $validated = $request->validate([
            'rating'  => 'required|integer|between:1,10',
            'comment' => 'nullable|string|max:2000',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        
        if ($user->reviews()->where('movie_id', $movie->id)->exists()) {
            return response()->json([
                'message' => 'You have already left a review for this movie'
            ], 422);
        }

        $review = $user->reviews()->create([
            'movie_id' => $movie->id,
            'rating'   => $validated['rating'],
            'comment'  => $validated['comment'] ?? null,
        ]);

        return response()->json($review->load('user'), 201);
    }

   
    public function update(Request $request, Review $review): JsonResponse
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating'  => 'sometimes|required|integer|between:1,10',
            'comment' => 'nullable|string|max:2000',
        ]);

        $review->update($validated);

        return response()->json($review->fresh()->load('user'));
    }

    
    public function destroy(Review $review): JsonResponse
    {
        $this->authorize('delete', $review);

        $review->delete();

        return response()->json(null, 204);
    }
}