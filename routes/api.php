<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\TestimoniController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('V1')->group(function () {
    Route::prefix('auth')
        ->controller(AuthController::class)
        ->group(function () {
            Route::post('/register', 'register');
            Route::post('/login', 'login')->middleware('throttle:5,1');
            Route::get('/oauth/google', 'oAuthUrl');
            Route::get('/oauth/google/callback', 'oAuthCallback');
            Route::middleware('auth:sanctum')->group(function () {
                Route::post('/logout', 'logout');
                Route::get('/profile', 'profile');
                Route::post('/update-profile', 'updateProfile');
                Route::post('/upload-avatar', 'uploadAvatar');
                Route::delete('/avatar', 'deleteAvatar');
                Route::post('/change-password', 'changePassword');
            });
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
