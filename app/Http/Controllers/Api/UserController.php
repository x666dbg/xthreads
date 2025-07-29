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
        // Load relationships
        $user->load(['threads.likes', 'threads.repostedBy', 'reposts.likes', 'reposts.repostedBy']);

        // Get user's threads
        $threads = $user->threads()
            ->with(['user', 'likes', 'repostedBy'])
            ->whereNull('parent_thread_id') // Only main threads, not replies
            ->latest()
            ->get();

        // Get user's reposts
        $reposts = $user->reposts()
            ->with(['user', 'likes', 'repostedBy'])
            ->get();

        // Format threads
        $formattedThreads = $threads->map(function ($thread) {
            return [
                'id' => $thread->id,
                'type' => 'thread',
                'content' => $thread->content,
                'image' => $thread->image ? asset('storage/' . $thread->image) : null,
                'likes_count' => $thread->likes->count(),
                'reposts_count' => $thread->repostedBy->count(),
                'replies_count' => $thread->replies->count(),
                'created_at' => $thread->created_at,
            ];
        });

        // Format reposts
        $formattedReposts = $reposts->map(function ($thread) use ($user) {
            return [
                'id' => $thread->id,
                'type' => 'repost',
                'content' => $thread->content,
                'image' => $thread->image ? asset('storage/' . $thread->image) : null,
                'original_user' => [
                    'id' => $thread->user->id,
                    'username' => $thread->user->username,
                ],
                'reposted_by' => [
                    'id' => $user->id,
                    'username' => $user->username,
                ],
                'likes_count' => $thread->likes->count(),
                'reposts_count' => $thread->repostedBy->count(),
                'replies_count' => $thread->replies->count(),
                'created_at' => $thread->pivot->created_at,
            ];
        });

        // Combine and sort timeline
        $timeline = $formattedThreads->concat($formattedReposts)
            ->sortByDesc('created_at')
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'followers_count' => $user->followers()->count(),
                    'following_count' => $user->following()->count(),
                    'threads_count' => $user->threads()->count(),
                ],
                'timeline' => $timeline
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
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(20)
            ->get();

        $formattedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'followers_count' => $user->followers()->count(),
                'following_count' => $user->following()->count(),
                'threads_count' => $user->threads()->count(),
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
