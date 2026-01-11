<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Watchlist;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    use AuthorizesRequests;

    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        return response()->json(
            $user->watchlists()->with('movie')->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $watchlist = $user->watchlists()->create([
            'movie_id' => $request->movie_id,
            'status'   => $request->status ?? 'plan_to_watch',
            'rating'   => $request->rating,
        ]);

        return response()->json($watchlist, 201);
    }

    public function update(Request $request, Watchlist $watchlist): JsonResponse
    {
        $this->authorize('update', $watchlist);

        $watchlist->update($request->only(['status', 'rating', 'note']));

        return response()->json($watchlist);
    }

    public function destroy(Watchlist $watchlist): JsonResponse
    {
        $this->authorize('delete', $watchlist);

        $watchlist->delete();

        return response()->json(null, 204);
    }

    
    public function test(): string
    {
        return User::class;
    }
}