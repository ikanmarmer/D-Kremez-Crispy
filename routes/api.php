<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\TestimoniController;
use App\Http\Controllers\API\V1\NotificationController;
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
            // Public routes - TANPA AUTHENTICATION
            Route::post('/register', 'register');
            Route::post('/login', 'login');
            Route::post('/verify-code', 'verifyCode');
            Route::post('/resend-verification-code', 'resendVerificationCode');
            Route::post('/check-registration-status', 'checkRegistrationStatus');
            Route::post('/setup-profile', 'setupProfile'); // PERBAIKAN: PINDAHKAN KE LUAR MIDDLEWARE

            // Google OAuth Routes
            Route::get('/google', 'redirectToGoogle');
            Route::get('/google/callback', 'handleGoogleCallback');
            Route::post('/google/login', 'loginWithGoogle');

            // Protected routes - DENGAN AUTHENTICATION
            Route::middleware('auth:sanctum')->group(function () {
                Route::post('/logout', 'logout');
                Route::get('/profile', 'profile');
                Route::post('/update-profile', 'updateProfile');
                Route::delete('/avatar', 'deleteAvatar');
                Route::post('/change-password', 'changePassword');
            });
        });

    Route::prefix('testimonials')
        ->controller(TestimoniController::class)
        ->group(function () {
            // Public routes - bisa diakses tanpa login
            Route::get('/approved', 'getApprovedTestimonials');

            // Protected routes - butuh authentication
            Route::middleware('auth:sanctum')->group(function () {
                Route::post('/', 'submitTestimonial');
                Route::get('/check', 'checkTestimonialStatus'); // DIUBAH: dari hasSubmittedTestimonial menjadi checkTestimonialStatus
                Route::post('/mark-notified', 'markAsNotified');
                Route::get('/user', 'getUserTestimonial'); // DIUBAH: dari my-testimonial menjadi user

                // Routes untuk admin (jika diperlukan)
                Route::put('/{id}/approve', 'approveTestimonial');
                Route::put('/{id}/reject', 'rejectTestimonial');
            });
        });

    // TAMBAHKAN ROUTES UNTUK NOTIFICATIONS
    Route::prefix('notifications')
        ->controller(NotificationController::class)
        ->middleware('auth:sanctum')
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/unread-count', 'unreadCount');
            Route::post('/{id}/read', 'markAsRead');
            Route::post('/read-all', 'markAllAsRead');
            Route::delete('/{id}', 'destroy');
        });

});
