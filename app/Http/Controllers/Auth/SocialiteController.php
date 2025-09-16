<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\Enums\Role;

class SocialiteController extends Controller
{
    /**
     * Redirect ke provider (Google/Facebook/Github)
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle callback dari provider (Google/Facebook/Github)
     */
    public function handleProviderCallback(string $provider)
    {
        $frontendUrl = config('app.frontend_url', 'http://localhost:5173');

        try {
            // Ambil data user dari provider
            $socialUser = Socialite::driver($provider)->user();

            // Cek apakah user sudah ada
            $existingUser = User::where('email', $socialUser->getEmail())->first();

            if ($existingUser) {
                // Kalau sudah ada → login langsung
                Auth::login($existingUser);
                $token = $existingUser->createToken('auth_token')->plainTextToken;

                if ($existingUser->profile_completed) {
                    // Profil lengkap → arahkan ke halaman utama
                    return redirect()->to("{$frontendUrl}?token={$token}");
                }

                // Profil belum lengkap → arahkan ke setup profile
                return redirect()->to("{$frontendUrl}/setup-profile?token={$token}");
            }

            // Kalau user belum ada → buat baru
            $user = User::create([
                'email'             => $socialUser->getEmail(),
                'name'              => $socialUser->getName() ?? null,
                'password'          => null, // Password akan diatur saat setup profile
                'email_verified_at' => now(), // Langsung verified via OAuth
                'role'              => Role::User,
                'profile_completed' => false, // User wajib setup profile
            ]);

            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;

            // Arahkan ke setup profile
            return redirect()->to("{$frontendUrl}/setup-profile?token={$token}");

        } catch (\Throwable $e) {
            Log::error("Socialite login error ({$provider}): " . $e->getMessage());
            return redirect()->to("{$frontendUrl}/login?error=social_login_failed");
        }
    }
}
