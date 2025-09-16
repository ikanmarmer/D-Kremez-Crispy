<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/user', function (Request $request) {
                return $request->user();
            });
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::post('/setup-profile', [AuthController::class, 'setupProfile']);
            Route::post('/update-profile', [AuthController::class, 'updateProfile']);
            Route::post('/upload-avatar', [AuthController::class, 'uploadAvatar']);
            Route::post('/delete-avatar', [AuthController::class, 'deleteAvatar']);
        });

        // Public routes
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/verify-code', [AuthController::class, 'verifyCode']);
        Route::post('/resend-verification-code', [AuthController::class, 'resendVerificationCode']);
    });
});
