<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// Route::get('/', Login::class)->name('home');
Route::get('/', function () {
    return redirect()->to(url('/login'));
})->name('home');


// Route::get('/{provider}/redirect', [SocialiteController::class, 'redirectToProvider']);
// Route::get('/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);

Route::get('/tes-email', function () {
    Mail::raw('Tes kirim email dari Laravel', function($m) {
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

