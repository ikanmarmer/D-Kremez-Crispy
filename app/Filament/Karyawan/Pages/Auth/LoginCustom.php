<?php

namespace App\Filament\Karyawan\Pages\Auth;

use App\Enums\Role;
use App\Models\User;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;

class LoginCustom extends BaseLogin
{
    /**
     * Override metode authenticate untuk menambahkan logika kustom.
     *
     * @throws ValidationException
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            // Jalankan proses autentikasi bawaan Filament
            return parent::authenticate();
        } catch (ValidationException $e) {
            $email = $this->form->getState()['email'];
            $user = User::where('email', $email)->first();

            // Cek apakah user ada dan memiliki role Admin
            if ($user && $user->role === Role::Admin) {
                throw ValidationException::withMessages([
                    'data.email' => 'Tidak dapat login ke panel karyawan menggunakan akun admin.',
                ]);
            }

            throw $e;
        }
    }
}
