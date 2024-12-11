<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes for getting authenticated user's information
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile routes
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);

    // Post routes
    Route::apiResource('posts', PostController::class);

    // Like routes
    Route::post('posts/{post}/like', [LikeController::class, 'store']);
    Route::delete('posts/{post}/like', [LikeController::class, 'destroy']);
    Route::get('posts/{post}/likes', [LikeController::class, 'index']);

    // Comment routes
    Route::get('posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('posts/{post}/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('posts/{post}/comments/{comment}', [CommentController::class, 'destroy']);
});
