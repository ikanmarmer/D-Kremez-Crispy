<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use App\Enums\Role;

class Login extends BaseLogin
{
    protected function getLoginRedirectUrl(): string
    {
        $user = auth()->user();

        if ($user && $user->role === Role::Admin) {
            return '/admin';
        }

        if ($user && $user->role === Role::Karyawan) {
            return '/karyawan';
        }

        return '/';
    }
}
