<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repost;
use App\Models\Thread;

class ThreadController extends Controller
{
    // Method untuk menampilkan semua thread di dashboard
    public function index(Request $request)
    {
        // 1. Ambil SEMUA thread asli, tidak lagi memfilter berdasarkan siapa yang di-follow.
        $threads = Thread::with('user', 'likes', 'repostedBy')
            ->latest()
            ->get();

        // 2. Ambil SEMUA repost.
        $reposts = Repost::with(['user', 'thread.user', 'thread.likes', 'thread.repostedBy'])
            ->latest()
            ->get();

        // 3. Gabungkan dan urutkan. Logika ini tetap sama.
        $timeline = $threads->map(function ($thread) {
            return (object) [
                'is_repost' => false,
                'reposted_by' => null,
                'original_thread' => $thread,
                'created_at' => $thread->created_at,
            ];
        })->concat($reposts->map(function ($repost) {
            return (object) [
                'is_repost' => true,
                'reposted_by' => $repost->user,
                'original_thread' => $repost->thread,
                'created_at' => $repost->created_at,
            ];
        }))
        ->sortByDesc('created_at');

        // 4. Kirim ke view.
        return view('dashboard', [
            'timeline' => $timeline,
        ]);
    }

    // Method untuk menyimpan thread baru
    public function store(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'content' => 'required|string|max:280',
        ]);

        // 2. Simpan ke database menggunakan relasi
        $request->user()->threads()->create($validated);

        // 3. Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect(route('dashboard'))->with('success', 'Thread berhasil diposting!');
    }

    public function show(Thread $thread)
    {
        // Muat relasi replies beserta user dari setiap reply
        $thread->load('replies.user');

        return view('threads.show', [
            'thread' => $thread
        ]);
    }
}
