<?php

use App\Http\Controllers\TestAPIController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


// Route::get('/test-api', [TestAPIController::class, 'showTestPage']);
