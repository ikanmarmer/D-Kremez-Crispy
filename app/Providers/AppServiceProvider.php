<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Filament\Facades\Filament;
use App\Models\Testimoni;
use App\Observers\TestimoniObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ✅ Langsung set default keyPath, jangan cek dulu
        Passport::$keyPath = storage_path();
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
