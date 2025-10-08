<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectGuestFromPanel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek kalau user belum login
        if (!Auth::check()) {
            $url = $request->path();

            if (str_starts_with($url, '/admin')) {
                return redirect('/login'); // atau redirect('/admin/login') jika kamu punya halaman khusus
            }

            if (str_starts_with($url, '/karyawan')) {
                return redirect('/'); // atau redirect('/karyawan/login')
            }
        }

        return $next($request);
    }
}
