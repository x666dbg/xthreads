<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // --- AWAL MODIFIKASI ---

        // 1. Cari user berdasarkan email yang diinput
        $user = User::where('email', $request->email)->first();

        // 2. Jika user ada dan statusnya di-ban, gagalkan login
        if ($user && $user->is_banned) {
            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah diblokir dan tidak bisa diakses.',
            ]);
        }

        // --- AKHIR MODIFIKASI ---


        // 3. Jika tidak di-ban, lanjutkan proses login seperti biasa
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
        // return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
