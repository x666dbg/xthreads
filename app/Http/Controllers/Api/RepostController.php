<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\Request;

class RepostController extends Controller
{
    /**
     * Repost a thread
     */
    public function store(Request $request, Thread $thread)
    {
        $user = $request->user();
        
        // Check if user is trying to repost their own thread
        if ($thread->user_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot repost your own thread'
            ], 400);
        }

        // Check if already reposted
        if ($user->reposts()->where('thread_id', $thread->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Thread already reposted'
            ], 400);
        }

        $user->reposts()->attach($thread);

        return response()->json([
            'success' => true,
            'message' => 'Thread reposted successfully',
            'data' => [
                'thread_id' => $thread->id,
                'reposts_count' => $thread->repostedBy()->count(),
                'is_reposted' => true
            ]
        ]);
    }

    /**
     * Remove repost
     */
    public function destroy(Request $request, Thread $thread)
    {
        $user = $request->user();
        
        // Check if not reposted
        if (!$user->reposts()->where('thread_id', $thread->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Thread not reposted yet'
            ], 400);
        }

        $user->reposts()->detach($thread);

        return response()->json([
            'success' => true,
            'message' => 'Repost removed successfully',
            'data' => [
                'thread_id' => $thread->id,
                'reposts_count' => $thread->repostedBy()->count(),
                'is_reposted' => false
            ]
        ]);
    }

    /**
     * Toggle repost status for a thread
     */
    public function toggle(Request $request, Thread $thread)
    {
        $user = $request->user();
        
        // Check if user is trying to repost their own thread
        if ($thread->user_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot repost your own thread'
            ], 400);
        }
        
        if ($user->reposts()->where('thread_id', $thread->id)->exists()) {
            $user->reposts()->detach($thread);
            $action = 'removed';
            $isReposted = false;
        } else {
            $user->reposts()->attach($thread);
            $action = 'reposted';
            $isReposted = true;
            
            // Send notification when reposting
            if ($thread->user_id !== $user->id) {
                $thread->user->notify(new \App\Notifications\ThreadRepostNotification($thread, $user));
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Thread {$action} successfully",
            'data' => [
                'thread_id' => $thread->id,
                'reposts_count' => $thread->repostedBy()->count(),
                'is_reposted' => $isReposted,
                'action' => $action
            ]
        ]);
    }

    /**
     * Get users who reposted a thread
     */
    public function getReposts(Thread $thread)
    {
        $reposts = $thread->repostedBy()->get();
        
        $users = $reposts->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'reposted_at' => $user->pivot->created_at
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'thread_id' => $thread->id,
                'reposts_count' => $reposts->count(),
                'users' => $users
            ]
        ]);
    }
}
