<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get user profile
     */
    public function show(User $user)
    {
        // Get authenticated user to check following status
        $currentUser = auth()->user();
        $isFollowing = false;

        if ($currentUser) {
            $isFollowing = $currentUser->following()
                ->where('following_user_id', $user->id)
                ->exists();
        }

        // Get user's threads - FIXED: removed parent_thread_id filter
        $threads = $user->threads()
            ->with(['user', 'likes', 'repostedBy'])
            ->latest()
            ->get();

        // Get user's reposts (if relationship exists)
        $reposts = collect([]);
        if (method_exists($user, 'reposts')) {
            $reposts = $user->reposts()
                ->with(['user', 'likes', 'repostedBy'])
                ->get();
        }

        // Format threads
        $formattedThreads = $threads->map(function ($thread) use ($currentUser) {
            $isLiked = false;
            if ($currentUser) {
                $isLiked = $thread->likes()
                    ->where('user_id', $currentUser->id)
                    ->exists();
            }

            return [
                'id' => $thread->id,
                'type' => 'thread',
                'title' => $thread->title ?? null,
                'body' => $thread->body ?? $thread->content ?? null,
                'content' => $thread->content ?? $thread->body ?? null,
                'image' => $thread->image ? asset('storage/' . $thread->image) : null,
                'likes_count' => $thread->likes->count(),
                'reposts_count' => method_exists($thread, 'repostedBy') ? $thread->repostedBy->count() : 0,
                'replies_count' => method_exists($thread, 'replies') ? $thread->replies->count() : 0,
                'is_liked' => $isLiked,
                'created_at' => $thread->created_at,
                'user' => [
                    'id' => $thread->user->id,
                    'username' => $thread->user->username,
                    'photo' => $thread->user->photo ? asset('storage/' . $thread->user->photo) : null,
                ],
            ];
        });

        // Format reposts
        $formattedReposts = $reposts->map(function ($thread) use ($user, $currentUser) {
            $isLiked = false;
            if ($currentUser) {
                $isLiked = $thread->likes()
                    ->where('user_id', $currentUser->id)
                    ->exists();
            }

            return [
                'id' => $thread->id,
                'type' => 'repost',
                'title' => $thread->title ?? null,
                'body' => $thread->body ?? $thread->content ?? null,
                'content' => $thread->content ?? $thread->body ?? null,
                'image' => $thread->image ? asset('storage/' . $thread->image) : null,
                'original_user' => [
                    'id' => $thread->user->id,
                    'username' => $thread->user->username,
                    'photo' => $thread->user->photo ? asset('storage/' . $thread->user->photo) : null,
                ],
                'reposted_by' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
                ],
                'likes_count' => $thread->likes->count(),
                'reposts_count' => method_exists($thread, 'repostedBy') ? $thread->repostedBy->count() : 0,
                'replies_count' => method_exists($thread, 'replies') ? $thread->replies->count() : 0,
                'is_liked' => $isLiked,
                'created_at' => $thread->pivot->created_at ?? $thread->created_at,
            ];
        });

        // Combine and sort timeline
        $timeline = $formattedThreads->concat($formattedReposts)
            ->sortByDesc('created_at')
            ->values();

        // Get likes count
        $likesCount = 0;
        if ($currentUser) {
            $likesCount = \DB::table('likes')
                ->where('user_id', $user->id)
                ->count();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
                    'cover_photo' => $user->cover_photo ? asset('storage/' . $user->cover_photo) : null,
                    'bio' => $user->bio ?? null,
                    'location' => $user->location ?? null,
                    'is_moderator' => $user->is_moderator ?? false,
                    'created_at' => $user->created_at,
                    'followers_count' => $user->followers()->count(),
                    'following_count' => $user->following()->count(),
                    'threads_count' => $user->threads()->count(),
                    'likes_count' => $likesCount,
                ],
                'timeline' => $timeline,
                'is_following' => $isFollowing,
            ]
        ]);
    }

    /**
     * Follow a user
     */
    public function follow(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Check if trying to follow self
        if ($currentUser->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot follow yourself'
            ], 400);
        }

        // Check if already following
        if ($currentUser->following()->where('following_user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Already following this user'
            ], 400);
        }

        $currentUser->following()->attach($user);

        return response()->json([
            'success' => true,
            'message' => "Successfully followed @{$user->username}",
            'data' => [
                'user_id' => $user->id,
                'username' => $user->username,
                'is_following' => true,
                'followers_count' => $user->followers()->count()
            ]
        ]);
    }

    /**
     * Unfollow a user
     */
    public function unfollow(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Check if not following
        if (!$currentUser->following()->where('following_user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Not following this user'
            ], 400);
        }

        $currentUser->following()->detach($user);

        return response()->json([
            'success' => true,
            'message' => "Successfully unfollowed @{$user->username}",
            'data' => [
                'user_id' => $user->id,
                'username' => $user->username,
                'is_following' => false,
                'followers_count' => $user->followers()->count()
            ]
        ]);
    }

    /**
     * Toggle follow status
     */
    public function toggleFollow(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Check if trying to follow self
        if ($currentUser->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot follow yourself'
            ], 400);
        }

        if ($currentUser->following()->where('following_user_id', $user->id)->exists()) {
            $currentUser->following()->detach($user);
            $action = 'unfollowed';
            $isFollowing = false;
        } else {
            $currentUser->following()->attach($user);
            $action = 'followed';
            $isFollowing = true;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully {$action} @{$user->username}",
            'data' => [
                'user_id' => $user->id,
                'username' => $user->username,
                'is_following' => $isFollowing,
                'followers_count' => $user->followers()->count(),
                'action' => $action
            ]
        ]);
    }

    /**
     * Get user's followers
     */
    public function followers(User $user)
    {
        $followers = $user->followers()->get();

        $formattedFollowers = $followers->map(function ($follower) {
            return [
                'id' => $follower->id,
                'username' => $follower->username,
                'photo' => $follower->photo ? asset('storage/' . $follower->photo) : null,
                'followed_at' => $follower->pivot->created_at
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $user->id,
                'username' => $user->username,
                'followers_count' => $followers->count(),
                'followers' => $formattedFollowers
            ]
        ]);
    }

    /**
     * Get user's following
     */
    public function following(User $user)
    {
        $following = $user->following()->get();

        $formattedFollowing = $following->map(function ($followedUser) {
            return [
                'id' => $followedUser->id,
                'username' => $followedUser->username,
                'photo' => $followedUser->photo ? asset('storage/' . $followedUser->photo) : null,
                'followed_at' => $followedUser->pivot->created_at
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $user->id,
                'username' => $user->username,
                'following_count' => $following->count(),
                'following' => $formattedFollowing
            ]
        ]);
    }

    /**
     * Search users
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1'
        ]);

        $query = $request->get('q');

        $users = User::where('username', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();

        $formattedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'query' => $query,
                'users' => $formattedUsers
            ]
        ]);
    }
}
