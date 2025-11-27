<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:160',
            'location' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048', // 2MB
            'cover_photo' => 'nullable|image|max:5120', // 5MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update text fields
        if ($request->has('username')) {
            $user->username = $request->username;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('bio')) {
            $user->bio = $request->bio;
        }
        if ($request->has('location')) {
            $user->location = $request->location;
        }

        // Handle profile photo
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->photo = $path;
        }

        // Handle cover photo
        if ($request->hasFile('cover_photo')) {
            // Delete old cover
            if ($user->cover_photo) {
                Storage::disk('public')->delete($user->cover_photo);
            }

            $path = $request->file('cover_photo')->store('cover-photos', 'public');
            $user->cover_photo = $path;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'bio' => $user->bio,
                    'location' => $user->location,
                    'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
                    'cover_photo' => $user->cover_photo ? asset('storage/' . $user->cover_photo) : null,
                ]
            ]
        ]);
    }

    public function delete(Request $request)
    {
        $user = $request->user();

        // Delete user's photos
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }
        if ($user->cover_photo) {
            Storage::disk('public')->delete($user->cover_photo);
        }

        // Delete user
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully'
        ]);
    }
}
