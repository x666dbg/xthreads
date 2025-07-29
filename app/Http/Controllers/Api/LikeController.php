<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Like a thread
     */
    public function likeThread(Request $request, Thread $thread)
    {
        $user = $request->user();
        
        // Check if already liked
        if ($thread->isLikedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Thread already liked'
            ], 400);
        }

        $thread->like($user);

        return response()->json([
            'success' => true,
            'message' => 'Thread liked successfully',
            'data' => [
                'thread_id' => $thread->id,
                'likes_count' => $thread->likes()->count(),
                'is_liked' => true
            ]
        ]);
    }

    /**
     * Unlike a thread
     */
    public function unlikeThread(Request $request, Thread $thread)
    {
        $user = $request->user();
        
        // Check if not liked
        if (!$thread->isLikedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Thread not liked yet'
            ], 400);
        }

        $thread->unlike($user);

        return response()->json([
            'success' => true,
            'message' => 'Thread unliked successfully',
            'data' => [
                'thread_id' => $thread->id,
                'likes_count' => $thread->likes()->count(),
                'is_liked' => false
            ]
        ]);
    }

    /**
     * Toggle like status for a thread
     */
    public function toggleLike(Request $request, Thread $thread)
    {
        $user = $request->user();
        
        if ($thread->isLikedBy($user)) {
            $thread->unlike($user);
            $action = 'unliked';
            $isLiked = false;
        } else {
            $thread->like($user);
            $action = 'liked';
            $isLiked = true;
        }

        return response()->json([
            'success' => true,
            'message' => "Thread {$action} successfully",
            'data' => [
                'thread_id' => $thread->id,
                'likes_count' => $thread->likes()->count(),
                'is_liked' => $isLiked,
                'action' => $action
            ]
        ]);
    }

    /**
     * Get users who liked a thread
     */
    public function getLikes(Thread $thread)
    {
        $likes = $thread->likes()->with('user:id,username')->get();
        
        $users = $likes->map(function ($like) {
            return [
                'id' => $like->user->id,
                'username' => $like->user->username,
                'liked_at' => $like->created_at
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'thread_id' => $thread->id,
                'likes_count' => $likes->count(),
                'users' => $users
            ]
        ]);
    }
}
