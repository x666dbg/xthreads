<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; // <-- Pastikan ini ada
use Illuminate\View\View;

class UserProfileController extends Controller
{
    public function show(User $user): View
    {
        return view('profile.show', [
            'user' => $user,
            'threads' => $user->threads()->latest()->get(),
        ]);
    }

    public function follow(Request $request, User $user): RedirectResponse // <-- Tambahkan Request
    {
        // Ganti auth()->user() menjadi request()->user()
        $request->user()->following()->attach($user);
        return back()->with('success', 'Berhasil mengikuti @' . $user->username);
    }

    public function unfollow(Request $request, User $user): RedirectResponse // <-- Tambahkan Request
    {
        // Ganti auth()->user() menjadi request()->user()
        $request->user()->following()->detach($user);
        return back()->with('success', 'Berhasil berhenti mengikuti @' . $user->username);
    }
}