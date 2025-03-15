<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| These routes are available to everyone.
|
*/

// Auth (registration and login are public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Publicly accessible content


/*
|--------------------------------------------------------------------------
| Protected Routes (require auth:sanctum)
|--------------------------------------------------------------------------
|
| These routes require the user to be authenticated.
|
*/
Route::middleware('auth:sanctum')->group(function () {
    // Get authenticated user  logout

    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('profile/{userId}', [ProfileController::class, 'show']);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('movies', MovieController::class);

    // Comments
    // For creating a comment, we use a nested route under posts
    Route::post('posts/{post}/comments', [CommentController::class, 'store']);
    // Other comment actions
    Route::apiResource('comments', CommentController::class)->except(['store']);

    // Likes (nested under posts)
    Route::post('posts/{post}/like', [LikeController::class, 'store']);
    Route::delete('posts/{post}/like', [LikeController::class, 'destroy']);
});
// Route::group(['middlewre'=>'auth:sanctum','prefix'=>'posts'],function(){

// });
