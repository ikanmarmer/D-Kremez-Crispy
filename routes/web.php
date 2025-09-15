<?php

use App\Http\Controllers\TestAPIController;
use Illuminate\Support\Facades\Route;
use App\Filament\Pages\Auth\Login;

Route::get('/', Login::class)->name('home');


// Route::get('/test-api', [TestAPIController::class, 'showTestPage']);
