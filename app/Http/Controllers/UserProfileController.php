<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class UserProfileController extends Controller
{
    public function show(User $user): View
    {
        // 1. Ambil semua THREAD ASLI yang dibuat oleh user ini.
        $threads = $user->threads()
            ->with('user', 'likes', 'repostedBy')
            ->latest()
            ->get();

        // 2. Ambil semua THREAD YANG DI-REPOST oleh user ini.
        // Kita gunakan relasi 'reposts()' yang sudah kita buat di model User.
        $reposts = $user->reposts()
            ->with('user', 'likes', 'repostedBy')
            ->get();

        // 3. Gabungkan keduanya, sama seperti di timeline utama.
        $timeline = $threads->map(function ($thread) {
            return (object) [
                'is_repost' => false,
                'reposted_by' => null,
                'original_thread' => $thread,
                'created_at' => $thread->created_at,
            ];
        })->concat($reposts->map(function ($repost) use ($user) {
            return (object) [
                'is_repost' => true,
                'reposted_by' => $user, // User yang punya profil ini
                'original_thread' => $repost, // Di sini, $repost adalah thread aslinya
                'created_at' => $repost->pivot->created_at, // Waktu repost diambil dari pivot table
            ];
        }))
        // 4. Urutkan timeline gabungan berdasarkan waktu terbaru.
        ->sortByDesc('created_at');

        // 5. Kirim data ke view.
        return view('profile.show', [
            'user' => $user,
            'timeline' => $timeline, // Ganti nama variabel dari 'threads' menjadi 'timeline'
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

    public function ban(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('ban', $user);

        $user->update(['is_banned' => true]);

        return back()->with('success', 'Berhasil mem-ban @' . $user->username);
    }

    public function unban(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('unban', $user);

        $user->update(['is_banned' => false]);

        return back()->with('success', 'Berhasil melakukan unban pada @' . $user->username);
    }
}