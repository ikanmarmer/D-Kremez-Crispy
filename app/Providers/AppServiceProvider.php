<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Filament\Facades\Filament;
use App\Models\Testimoni;
use App\Observers\TestimoniObserver;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\LogoutResponse;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ✅ Langsung set default keyPath, jangan cek dulu
        Passport::$keyPath = storage_path();

        $this->app->bind(LoginResponseContract::class, LoginResponse::class);
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            app()->setLocale('id');
        });

        Testimoni::observe(TestimoniObserver::class);

    }
}
