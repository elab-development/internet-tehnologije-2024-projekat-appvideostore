<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WatchlistController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Neispravni kredencijali'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('movies', MovieController::class)->only(['index', 'show']);

    Route::apiResource('watchlists', WatchlistController::class);

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/user', fn(Request $request) => $request->user());

    Route::get('/movies/search', [MovieController::class, 'search']); //search movies

    Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store']); //add review

    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']); //remove review
    
});



