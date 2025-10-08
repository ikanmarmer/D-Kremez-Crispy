<?php

use Filament\Facades\Filament;
use Filament\Pages\Dashboard;
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
Route::get('/', action: function () {
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



// Tes email
Route::get('/tes-email', function () {
    Mail::raw('Tes kirim email dari Laravel', function ($m) {
        $m->to('muhamadkeiza.ddd@gmail.com')->subject('Tes Email');
    });

    return 'Email berhasil dikirim';
});

// Google OAuth
Route::get('/auth/redirect', fn() => Socialite::driver('google')->redirect());

Route::get('/auth/callback', function () {
    $user = Socialite::driver('google')->user();
    // $user->token
});
