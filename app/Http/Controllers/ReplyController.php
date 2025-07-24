<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;

class ReplyController extends Controller
{
    public function store(Request $request, Thread $thread)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $thread->replies()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }
}