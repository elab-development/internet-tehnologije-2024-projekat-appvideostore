<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MovieController extends Controller
{
    use AuthorizesRequests;

    /**
     * Prikaz svih filmova (može biti i paginirano)
     */
    public function index(): JsonResponse
    {
        $movies = Movie::latest()->paginate(20);

        return response()->json($movies);
    }

    /**
     * Prikaz pojedinog filma sa recenzijama
     */
    public function show(Movie $movie): JsonResponse
    {
        $movie->load([
            'reviews' => fn($q) => $q->with('user')->latest()
        ]);

        return response()->json($movie);
    }

    /**
     * Kreiranje novog filma
     * (obično ovo rade samo admini – zato autorizacija)
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Movie::class);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'original_title'=> 'nullable|string|max:255',
            'release_year'  => 'required|integer|min:1880|max:' . (date('Y') + 5),
            'description'   => 'nullable|string',
            'director'      => 'nullable|string|max:255',
            'genre'         => 'nullable|string|max:100',
            'poster'        => 'nullable|url',
            'runtime'       => 'nullable|integer|min:1',
            'tmdb_id'       => 'nullable|integer|unique:movies,tmdb_id',
        ]);

        $movie = Movie::create($validated);

        return response()->json($movie, 201);
    }

    /**
     * Ažuriranje filma
     */
    public function update(Request $request, Movie $movie): JsonResponse
    {
        $this->authorize('update', $movie);

        $validated = $request->validate([
            'title'         => 'sometimes|required|string|max:255',
            'original_title'=> 'nullable|string|max:255',
            'release_year'  => 'sometimes|required|integer|min:1880|max:' . (date('Y') + 5),
            'description'   => 'nullable|string',
            'director'      => 'nullable|string|max:255',
            'genre'         => 'nullable|string|max:100',
            'poster'        => 'nullable|url',
            'runtime'       => 'nullable|integer|min:1',
        ]);

        $movie->update($validated);

        return response()->json($movie->fresh());
    }

    /**
     * Brisanje filma
     */
    public function destroy(Movie $movie): JsonResponse
    {
        $this->authorize('delete', $movie);

        $movie->delete();

        return response()->json(null, 204);
    }

    //search metoda
    public function search(Request $request): JsonResponse
    {
        $query = Movie::query();
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        return response()->json($query->get());
    }
}