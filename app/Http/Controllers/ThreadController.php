<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;

class ThreadController extends Controller
{
    // Method untuk menampilkan semua thread di dashboard
    public function index()
    {
        // Ambil semua thread, beserta data user-nya, urutkan dari yang terbaru
        $threads = Thread::with('user')->latest()->get();

        // Kirim data threads ke view 'dashboard'
        return view('dashboard', [
            'threads' => $threads,
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
}
