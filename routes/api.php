<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\TestimoniController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::prefix('auth')
        ->controller(AuthController::class)
        ->group(function () {
            Route::middleware('auth:sanctum')->group(function () {
                Route::post('/setup-profile', 'setupProfile');
                Route::post('/logout', 'logout');
                Route::get('/profile', 'profile');
                Route::post('/upload-avatar', 'uploadAvatar');
                Route::post('/update-profile', 'updateProfile');
                Route::delete('/avatar', 'deleteAvatar');
                Route::post('/change-password', 'changePassword');
            });

            // Public routes
            Route::post('/register', [AuthController::class, 'register']);
            Route::post('/login', [AuthController::class, 'login']);
            Route::post('/verify-code', [AuthController::class, 'verifyCode']);
            Route::post('/resend-verification-code', [AuthController::class, 'resendVerificationCode']);

        });
    Route::prefix('testimonials')
        ->controller(TestimoniController::class)
        ->group(function () {
            Route::get('/', 'getApprovedTestimonials');
            Route::middleware('auth:sanctum')->group(function () {
                Route::post('/', 'submitTestimonial');
                Route::get('/check', 'hasSubmittedTestimonial');
                Route::post('/mark-notified', 'markAsNotified');
                Route::get('/my-testimonial', 'getUserTestimonial');

            });
        });

});
