<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Notifications\ThreadLikedNotification;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likeThread(Request $request, Thread $thread)
    {
        $user = $request->user();
        $thread->like($user);
        
        if ($thread->user_id !== $user->id) {
            $thread->user->notify(new ThreadLikedNotification($thread, $user));
        }
        
        return back();
    }

    public function unlikeThread(Request $request, Thread $thread)
    {
        $thread->unlike($request->user());
        return back();
    }
}
