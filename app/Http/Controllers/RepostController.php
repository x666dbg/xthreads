<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class RepostController extends Controller
{
    public function store(Request $request, Thread $thread)
    {
        $request->user()->reposts()->syncWithoutDetaching($thread);
        return back();
    }

    public function destroy(Request $request, Thread $thread)
    {
        $request->user()->reposts()->detach($thread);
        return back();
    }
}
