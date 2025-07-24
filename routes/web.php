<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\UserProfileController; // <-- Pastikan ini ada

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Arahkan halaman utama ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route untuk timeline (dashboard)
Route::get('/dashboard', [ThreadController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Routes untuk Threads & Replies
    Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');
    Route::get('/threads/{thread}', [ThreadController::class, 'show'])->name('threads.show');
    Route::post('/threads/{thread}/replies', [ReplyController::class, 'store'])->name('replies.store');

    // Routes untuk PROFIL PUBLIK (yang kita buat)
    Route::get('/{user:username}', [UserProfileController::class, 'show'])->name('profile.show');
    Route::post('/{user:username}/follow', [UserProfileController::class, 'follow'])->name('profile.follow');
    Route::post('/{user:username}/unfollow', [UserProfileController::class, 'unfollow'])->name('profile.unfollow');

    // Routes untuk PENGATURAN PROFIL (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';