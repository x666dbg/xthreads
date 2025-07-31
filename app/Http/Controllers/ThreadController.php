<?php

namespace App\Http\Controllers;

use App\Models\Repost;
use App\Models\Thread;
use App\Notifications\ThreadReplyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ThreadController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $threadRelationships = ['user', 'likes', 'repostedBy', 'children', 'parent.user'];
        
        $threads = Thread::whereNull('parent_id')
            ->with($threadRelationships)
            ->latest()
            ->get();

        $reposts = Repost::with([
                'user',
                'thread' => function ($query) use ($threadRelationships) {
                    $query->with($threadRelationships);
                }
            ])
            ->latest()
            ->get();

        $timeline = $threads->map(function ($thread) {
            return (object) [
                'is_repost' => false,
                'reposted_by' => null,
                'original_thread' => $thread,
                'created_at' => $thread->created_at,
            ];
        })->concat($reposts->map(function ($repost) {
            if (!$repost->thread) {
                return null;
            }
            return (object) [
                'is_repost' => true,
                'reposted_by' => $repost->user,
                'original_thread' => $repost->thread,
                'created_at' => $repost->created_at,
            ];
        }))
        ->whereNotNull('original_thread')
        ->sortByDesc('created_at');

        return view('dashboard', [
            'timeline' => $timeline,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:280',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'parent_id' => 'nullable|exists:threads,id',
        ]);

        if ($request->hasFile('image')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('image'));
            $image->scale(width: 800);
            $fileName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $path = "threads/{$fileName}";
            Storage::disk('public')->put($path, $image->toJpeg(80));
            $validated['image'] = $path;
        }

        $thread = $request->user()->threads()->create($validated);

        if (isset($validated['parent_id']) && $validated['parent_id']) {
            $parentThread = Thread::find($validated['parent_id']);
            if ($parentThread && $parentThread->user_id !== $request->user()->id) {
                $parentThread->user->notify(new ThreadReplyNotification($parentThread, $thread, $request->user()));
            }
        }

        return back()->with('success', 'Postingan berhasil dibuat!');
    }

    public function show(Thread $thread)
    {
        $thread->load([
            'user',
            'parent.user',
            'likes',
            'repostedBy',
            'children'
        ]);

        return view('threads.show', [
            'thread' => $thread
        ]);
    }

    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        return back()->with('success', 'Thread berhasil dihapus!');
    }
}