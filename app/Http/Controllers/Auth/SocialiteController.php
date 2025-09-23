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
            $socialUser = Socialite::driver($provider)->user();

            $existingUser = User::where('email', $socialUser->getEmail())->first();

            if ($existingUser) {
                Auth::login($existingUser);
                $token = $existingUser->createToken('auth_token')->plainTextToken;

                if ($existingUser->profile_completed) {
                    return redirect()->to("{$frontendUrl}?token={$token}");
                }

                return redirect()->to("{$frontendUrl}/setup-profile?token={$token}");
            }

            $user = User::create([
                'email'             => $socialUser->getEmail(),
                'name'              => $socialUser->getName() ?? null,
                'password'          => null,
                'email_verified_at' => now(),
                'role'              => Role::User,
                'profile_completed' => false,
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
