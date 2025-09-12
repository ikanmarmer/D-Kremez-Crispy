<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\TestimoniController;
use App\Http\Controllers\API\V1\NotifikasiController;
use App\Http\Controllers\API\V1\VerifikasiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('V1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware(('auth:sanctum'))->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::put('/profile', [AuthController::class, 'updateProfile']);
            Route::post('/upload-avatar', [AuthController::class, 'uploadAvatar']);
            Route::delete('/avatar', [AuthController::class, 'deleteAvatar']);
        });
    });

    // Routes untuk testimoni
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/testimonials', [TestimoniController::class, 'index']);
        Route::post('/testimonials', [TestimoniController::class, 'store']);

        // Routes untuk notifikasi
        Route::get('/notifications', [NotifikasiController::class, 'index']);
        Route::get('/notifications/unread-count', [NotifikasiController::class, 'unreadCount']);
        Route::post('/notifications/{id}/read', [NotifikasiController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotifikasiController::class, 'markAllAsRead']);
    });

    // Routes admin untuk verifikasi testimoni
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/admin/testimonials/pending', [VerifikasiController::class, 'pendingTestimonials']);
        Route::post('/admin/testimonials/{id}/approve', [VerifikasiController::class, 'approveTestimonial']);
        Route::post('/admin/testimonials/{id}/reject', [VerifikasiController::class, 'rejectTestimonial']);
    });
});
