<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use App\Enums\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route utama dengan pengecekan autentikasi
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        // Redirect berdasarkan role user yang sudah login
        return match ($user->role) {
            Role::Admin => redirect()->to('/admin'),
            Role::Karyawan => redirect()->to('/karyawan'),
            default => redirect()->to('/login'),
        };
    }

    // Jika belum login, redirect ke halaman login
    return redirect()->to('/login');
})->name('home');


Route::get('/tes-email', function () {
    Mail::raw('Tes kirim email dari Laravel', function ($m) {
        $m->to('muhamadkeiza.ddd@gmail.com')->subject('Tes Email');
    });

    return 'Email berhasil dikirim';
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/callback', function () {
    $user = Socialite::driver('google')->user();

    // $user->token
});
