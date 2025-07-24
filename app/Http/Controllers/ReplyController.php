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
            'parent_id' => 'nullable|exists:replies,id'
        ]);

        $validated['user_id'] = $request->user()->id;

        $thread->replies()->create($validated);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }
}