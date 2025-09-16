<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Enums\Role;

class SocialiteController extends Controller
{
    /**
     * Redirect ke provider (Google/Facebook/Github)
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle callback dari provider
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            $frontendUrl = config('app.frontend_url', 'http://localhost:5173');

            $existingUser = User::where('email', $socialUser->getEmail())->first();

            if ($existingUser) {
                // User sudah ada
                if ($existingUser->profile_completed) {
                    // Profil sudah lengkap - redirect ke halaman utama
                    Auth::login($existingUser);
                    $token = $existingUser->createToken('auth_token')->plainTextToken;
                    return redirect()->to($frontendUrl . '?token=' . $token);
                } else {
                    // Profil belum lengkap - ke setup profile
                    Auth::login($existingUser);
                    $token = $existingUser->createToken('auth_token')->plainTextToken;
                    return redirect()->to($frontendUrl . '/setup-profile?token=' . $token);
                }
            } else {
                // Buat user baru dari Google - langsung terverifikasi
                $user = User::create([
                    'email' => $socialUser->getEmail(),
                    'name' => $socialUser->getName(), // Ambil nama dari Google jika ada
                    'password' => null, // Akan diset di setup profile
                    'email_verified_at' => now(), // Langsung terverifikasi untuk OAuth
                    'role' => Role::User,
                    'profile_completed' => false, // Masih perlu setup password
                ]);

                Auth::login($user);
                $token = $user->createToken('auth_token')->plainTextToken;

                // Redirect ke setup profile untuk set password
                return redirect()->to($frontendUrl . '/setup-profile?token=' . $token);
            }

        } catch (\Exception $e) {
            Log::error('Socialite login error: ' . $e->getMessage());
            $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
            return redirect($frontendUrl . '/login?error=social_login_failed');
        }
    }
}
