<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\RepostController;
use App\Http\Controllers\UserProfileController;

// 1. Route utama, arahkan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Route dashboard, sangat spesifik
Route::get('/dashboard', [ThreadController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// 3. Masukkan semua route dari Breeze (login, register, dll) SEBELUM route rakus
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    // 4. Routes spesifik lainnya (threads, replies, edit profil)
    Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');
    Route::get('/threads/{thread}', [ThreadController::class, 'show'])->name('threads.show');
    Route::post('/threads/{thread}/replies', [ReplyController::class, 'store'])->name('replies.store');

    Route::post('/threads/{thread}/like', [LikeController::class, 'likeThread'])->name('threads.like');
    Route::delete('/threads/{thread}/unlike', [LikeController::class, 'unlikeThread'])->name('threads.unlike');
    Route::post('/replies/{reply}/like', [LikeController::class, 'likeReply'])->name('replies.like');
    Route::delete('/replies/{reply}/unlike', [LikeController::class, 'unlikeReply'])->name('replies.unlike');

    Route::post('/threads/{thread}/repost', [RepostController::class, 'store'])->name('threads.repost');
    Route::delete('/threads/{thread}/repost', [RepostController::class, 'destroy'])->name('threads.repost.destroy');

    // Route untuk PENGATURAN PROFIL (bawaan Breeze).
    // Ini harus ada SEBELUM route profil publik.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // 5. Terakhir, letakkan route yang "rakus" di bagian PALING BAWAH
    // Ini adalah route untuk PROFIL PUBLIK.
    Route::get('/{user:username}', [UserProfileController::class, 'show'])->name('profile.show');
    Route::post('/{user:username}/follow', [UserProfileController::class, 'follow'])->name('profile.follow');
    Route::post('/{user:username}/unfollow', [UserProfileController::class, 'unfollow'])->name('profile.unfollow');
});