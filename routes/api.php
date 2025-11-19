<?php

use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BugController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\VoteController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public read-only routes
Route::get('/apps', [AppController::class, 'index']);
Route::get('/apps/{app}', [AppController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/bugs', [BugController::class, 'index']);
Route::get('/bugs/{bug}', [BugController::class, 'show']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Bug routes
    Route::post('/bugs', [BugController::class, 'store']);
    Route::put('/bugs/{bug}', [BugController::class, 'update']);
    Route::delete('/bugs/{bug}', [BugController::class, 'destroy']);

    // Comment routes
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // Vote routes
    Route::post('/bugs/{bug}/vote', [VoteController::class, 'toggle']);
    Route::get('/bugs/{bug}/vote/check', [VoteController::class, 'check']);

    // App management routes (admin only - checked in controller)
    Route::post('/apps', [AppController::class, 'store']);
    Route::put('/apps/{app}', [AppController::class, 'update']);
    Route::delete('/apps/{app}', [AppController::class, 'destroy']);

    // Category management routes (admin only - checked in controller)
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
});
