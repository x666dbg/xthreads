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
        $user = $request->user();

        // Ambil ID orang yang di-follow, dan tambahkan ID user sendiri
        $followingIds = $user->following()->pluck('id')->push($user->id);

        // Ambil thread asli dari orang-orang tersebut
        $threads = Thread::whereIn('user_id', $followingIds)
            ->with('user', 'likes') // Eager load relasi
            ->latest()
            ->get();

        // Ambil reposts dari orang-orang tersebut
        // Di sini kita ubah struktur data agar mirip dengan 'thread'
        // $reposts = \App\Models\Repost::whereIn('user_id', $followingIds)
        $reposts = Repost::whereIn('user_id', $followingIds)
            ->with(['user', 'thread.user', 'thread.likes']) // Eager load relasi dalam
            ->latest()
            ->get()
            ->map(function ($repost) {
                // Kita buat 'objek' baru yang seragam
                return (object) [
                    'is_repost' => true,
                    'reposted_by' => $repost->user, // Siapa yang me-repost
                    'original_thread' => $repost->thread, // Thread aslinya
                    'created_at' => $repost->created_at, // Waktu repost
                ];
            });

        // Gabungkan thread asli dan repost, lalu urutkan berdasarkan waktu terbaru
        $timeline = $threads->map(function($thread) {
            return (object) [
                'is_repost' => false,
                'original_thread' => $thread,
                'created_at' => $thread->created_at,
            ];
        })->concat($reposts)->sortByDesc('created_at');

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
