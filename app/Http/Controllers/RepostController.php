<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Notifications\ThreadRepostNotification;
use Illuminate\Http\Request;

class RepostController extends Controller
{
    public function store(Request $request, Thread $thread)
    {
        $user = $request->user();
        $user->reposts()->syncWithoutDetaching($thread);
        
        if ($thread->user_id !== $user->id) {
            $thread->user->notify(new ThreadRepostNotification($thread, $user));
        }
        
        return back();
    }

    public function destroy(Request $request, Thread $thread)
    {
        $request->user()->reposts()->detach($thread);
        return back();
    }
}
