<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Repost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ThreadController extends Controller
{
    /**
     * Get all threads (timeline)
     */
    public function index(Request $request)
    {
        // Ambil semua thread asli
        $threads = Thread::with(['user', 'likes', 'repostedBy'])
            ->whereNull('parent_thread_id') // Hanya thread utama, bukan reply
            ->latest()
            ->get();

        // Ambil semua repost
        $reposts = Repost::with(['user', 'thread.user', 'thread.likes', 'thread.repostedBy'])
            ->latest()
            ->get();

        // Gabungkan dan format data
        $timeline = collect();

        foreach ($threads as $thread) {
            $timeline->push([
                'id' => $thread->id,
                'type' => 'thread',
                'content' => $thread->content,
                'image' => $thread->image ? asset('storage/' . $thread->image) : null,
                'user' => [
                    'id' => $thread->user->id,
                    'username' => $thread->user->username,
                ],
                'likes_count' => $thread->likes->count(),
                'reposts_count' => $thread->repostedBy->count(),
                'replies_count' => $thread->replies->count(),
                'is_liked' => $request->user() ? $thread->isLikedBy($request->user()) : false,
                'is_reposted' => $request->user() ? $thread->repostedBy->contains($request->user()) : false,
                'created_at' => $thread->created_at,
            ]);
        }

        foreach ($reposts as $repost) {
            $timeline->push([
                'id' => $repost->thread->id,
                'type' => 'repost',
                'content' => $repost->thread->content,
                'image' => $repost->thread->image ? asset('storage/' . $repost->thread->image) : null,
                'original_user' => [
                    'id' => $repost->thread->user->id,
                    'username' => $repost->thread->user->username,
                ],
                'reposted_by' => [
                    'id' => $repost->user->id,
                    'username' => $repost->user->username,
                ],
                'likes_count' => $repost->thread->likes->count(),
                'reposts_count' => $repost->thread->repostedBy->count(),
                'replies_count' => $repost->thread->replies->count(),
                'is_liked' => $request->user() ? $repost->thread->isLikedBy($request->user()) : false,
                'is_reposted' => $request->user() ? $repost->thread->repostedBy->contains($request->user()) : false,
                'created_at' => $repost->created_at,
            ]);
        }

        // Urutkan berdasarkan waktu terbaru
        $timeline = $timeline->sortByDesc('created_at')->values();

        return response()->json([
            'success' => true,
            'data' => [
                'timeline' => $timeline
            ]
        ]);
    }

    /**
     * Create a new thread
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_thread_id' => 'nullable|exists:threads,id'
        ]);

        $threadData = [
            'content' => $request->content,
            'user_id' => $request->user()->id,
            'parent_thread_id' => $request->parent_thread_id,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();

            // Resize image
            $manager = new ImageManager(new Driver());
            $resizedImage = $manager->read($image->getPathname())
                ->resize(800, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

            // Save to storage
            $path = 'images/' . $filename;
            Storage::disk('public')->put($path, $resizedImage->encode());
            $threadData['image'] = $path;
        }

        $thread = Thread::create($threadData);
        $thread->load(['user', 'likes', 'repostedBy', 'replies']);

        return response()->json([
            'success' => true,
            'message' => 'Thread created successfully',
            'data' => [
                'thread' => [
                    'id' => $thread->id,
                    'content' => $thread->content,
                    'image' => $thread->image ? asset('storage/' . $thread->image) : null,
                    'user' => [
                        'id' => $thread->user->id,
                        'username' => $thread->user->username,
                    ],
                    'likes_count' => $thread->likes->count(),
                    'reposts_count' => $thread->repostedBy->count(),
                    'replies_count' => $thread->replies->count(),
                    'is_reply' => !is_null($thread->parent_thread_id),
                    'parent_thread_id' => $thread->parent_thread_id,
                    'created_at' => $thread->created_at,
                ]
            ]
        ], 201);
    }

    /**
     * Get a specific thread with replies
     */
    public function show(Thread $thread)
    {
        $thread->load(['user', 'likes', 'repostedBy', 'replies.user', 'replies.likes']);

        $replies = $thread->replies->map(function ($reply) {
            return [
                'id' => $reply->id,
                'content' => $reply->content,
                'image' => $reply->image ? asset('storage/' . $reply->image) : null,
                'user' => [
                    'id' => $reply->user->id,
                    'username' => $reply->user->username,
                ],
                'likes_count' => $reply->likes->count(),
                'created_at' => $reply->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'thread' => [
                    'id' => $thread->id,
                    'content' => $thread->content,
                    'image' => $thread->image ? asset('storage/' . $thread->image) : null,
                    'user' => [
                        'id' => $thread->user->id,
                        'username' => $thread->user->username,
                    ],
                    'likes_count' => $thread->likes->count(),
                    'reposts_count' => $thread->repostedBy->count(),
                    'replies_count' => $thread->replies->count(),
                    'created_at' => $thread->created_at,
                ],
                'replies' => $replies
            ]
        ]);
    }

    /**
     * Delete a thread
     */
    public function destroy(Thread $thread, Request $request)
    {
        if ($thread->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this thread'
            ], 403);
        }

        // Delete image if exists
        if ($thread->image) {
            Storage::disk('public')->delete($thread->image);
        }

        $thread->delete();

        return response()->json([
            'success' => true,
            'message' => 'Thread deleted successfully'
        ]);
    }
}
