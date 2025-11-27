<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the full profile edit form.
     */
    public function editFull(Request $request): View
    {
        return view('profile.edit-full', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's full profile information.
     */
    public function updateFull(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $request->user()->id],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'bio' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'cover_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'current_password' => ['nullable', 'current_password'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user = $request->user();

        // Handle profile photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('photo'));
            $image->scale(width: 400);
            $fileName = 'profile_' . uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = "profiles/{$fileName}";
            Storage::disk('public')->put($path, $image->toJpeg(80));
            $validated['photo'] = $path;
        }

        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            // Delete old cover photo if exists
            if ($user->cover_photo) {
                Storage::disk('public')->delete($user->cover_photo);
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('cover_photo'));
            $image->scale(width: 1200);
            $fileName = 'cover_' . uniqid() . '.' . $request->file('cover_photo')->getClientOriginalExtension();
            $path = "covers/{$fileName}";
            Storage::disk('public')->put($path, $image->toJpeg(80));
            $validated['cover_photo'] = $path;
        }

        // Handle password update
        if ($request->filled('password')) {
            if (!$request->filled('current_password')) {
                return back()->withErrors(['current_password' => 'Current password is required to set a new password.']);
            }
            // Password will be automatically hashed by the model
        } else {
            // Remove password from validated data if not being updated
            unset($validated['password']);
        }

        // Remove password confirmation and current_password from validated data
        unset($validated['password_confirmation'], $validated['current_password']);

        // Update user
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit-full')->with('status', 'profile-updated');
    }
}
