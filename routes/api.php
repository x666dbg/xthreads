<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ThreadController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\RepostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotificationController;

// Public routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {

    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // Thread routes
    Route::prefix('threads')->group(function () {
        Route::get('/', [ThreadController::class, 'index']); // Get timeline
        Route::post('/', [ThreadController::class, 'store']); // Create thread
        Route::get('/{thread}', [ThreadController::class, 'show']); // Get thread with replies
        Route::delete('/{thread}', [ThreadController::class, 'destroy']); // Delete thread

        // Like routes
        Route::post('/{thread}/like', [LikeController::class, 'likeThread']);
        Route::delete('/{thread}/like', [LikeController::class, 'unlikeThread']);
        Route::post('/{thread}/toggle-like', [LikeController::class, 'toggleLike']);
        Route::get('/{thread}/likes', [LikeController::class, 'getLikes']);

        // Repost routes
        Route::post('/{thread}/repost', [RepostController::class, 'store']);
        Route::delete('/{thread}/repost', [RepostController::class, 'destroy']);
        Route::post('/{thread}/toggle-repost', [RepostController::class, 'toggle']);
        Route::get('/{thread}/reposts', [RepostController::class, 'getReposts']);
    });

    // User routes
    Route::prefix('users')->group(function () {
        Route::get('/search', [UserController::class, 'search']); // Search users
        Route::get('/{user}', [UserController::class, 'show']); // Get user profile
        Route::get('/{user}/followers', [UserController::class, 'followers']); // Get followers
        Route::get('/{user}/following', [UserController::class, 'following']); // Get following

        // Follow routes
        Route::post('/{user}/follow', [UserController::class, 'follow']);
        Route::delete('/{user}/follow', [UserController::class, 'unfollow']);
        Route::post('/{user}/toggle-follow', [UserController::class, 'toggleFollow']);
    });

    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']); // Get notifications
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']); // Get unread count
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']); // Mark as read
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']); // Mark all as read
    });

    // Legacy route for compatibility
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
