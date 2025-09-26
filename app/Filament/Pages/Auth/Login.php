<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use App\Enums\Role;

class Login extends BaseLogin
{
    protected function getLoginRedirectUrl(): string
    {
        $user = auth()->user();

        if ($user && $user->role === Role::Admin->value) {
            return filament()->getPanel('admin')->getUrl();
        }

        if ($user && $user->role === Role::Karyawan->value) {
            return filament()->getPanel('karyawan')->getUrl();
        }

        return filament()->getPanel('login')->getUrl(); // fallback
    }
}
