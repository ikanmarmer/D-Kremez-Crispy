<?php

namespace App\Http\Responses;

use App\Enums\Role;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();

        // Redirect based on user role
        return match ($user->role) {
            Role::Admin => redirect()->intended('/admin'),
            Role::Karyawan => redirect()->intended('/karyawan'),
            default => redirect()->intended('/login'),
        };
    }
}
