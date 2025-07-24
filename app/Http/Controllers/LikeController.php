<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likeThread(Request $request, Thread $thread)
    {
        $thread->like($request->user());
        return back();
    }

    public function unlikeThread(Request $request, Thread $thread)
    {
        $thread->unlike($request->user());
        return back();
    }

    public function likeReply(Request $request, Reply $reply)
    {
        $reply->like($request->user());
        return back();
    }

    public function unlikeReply(Request $request, Reply $reply)
    {
        $reply->unlike($request->user());
        return back();
    }
}
