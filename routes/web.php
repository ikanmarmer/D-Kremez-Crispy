<?php

use App\Http\Controllers\TestAPIController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Mail;
use App\Filament\Pages\Auth\Login;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', Login::class)->name('home');

Route::get('/{provider}/redirect', [SocialiteController::class, 'redirectToProvider']);
Route::get('/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);

Route::get('/tes-email', function () {
    Mail::raw('Tes kirim email dari Laravel', function($m) {
        $m->to('muhamadkeiza.ddd@gmail.com')->subject('Tes Email');
    });

    return 'Email berhasil dikirim';
});
