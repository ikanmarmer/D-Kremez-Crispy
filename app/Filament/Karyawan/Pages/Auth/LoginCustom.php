<?php

namespace App\Filament\Karyawan\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use App\Enums\Role;
use App\Models\User;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;

class LoginCustom extends BaseLogin
{
    /**
     * Override metode authenticate untuk menambahkan logika kustom.
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            // Jalankan proses autentikasi bawaan Filament
            return parent::authenticate();
        } catch (ValidationException $e) {
            // Tangkap exception jika autentikasi GAGAL (password/email salah)

            $email = $this->form->getState()['email'];
            $user = User::where('email', $email)->first();

            // Cek apakah user ada dan memiliki role Admin
            if ($user && $user->role === Role::Admin) {
                // Jika ya, lempar exception baru dengan pesan kustom Anda
                throw ValidationException::withMessages([
                    'data.email' => 'kenapa kamu mencoba login ke panel karyawan menggunakan akun admin 🤨',
                ]);
            }

            // Jika bukan karena masalah role, lempar kembali exception aslinya
            throw $e;
        }
    }
}
