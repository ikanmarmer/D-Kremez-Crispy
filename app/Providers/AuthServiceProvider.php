<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Model::class => Policy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // ✅ Kalau mau custom folder untuk key
        // Pastikan file oauth-public.key & oauth-private.key sudah ada di storage/oauth
        // Passport::loadKeysFrom(storage_path('oauth'));

        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
