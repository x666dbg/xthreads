<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user sudah login DAN statusnya di-ban
        if (Auth::check() && Auth::user()->is_banned) {
            
            // Paksa logout
            Auth::logout();

            // Hapus sesi untuk keamanan
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // "Tendang" ke halaman login dengan pesan error
            return redirect()->route('login')->with('error', 'Akses Anda diblokir oleh administrator.');
        }

        // Jika tidak di-ban, biarkan user melanjutkan
        return $next($request);
    }
}
