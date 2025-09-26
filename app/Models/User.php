<?php

namespace App\Models;

use App\Enums\Role;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'email_verification_code',
        'password',
        'role',
        'avatar',
        'profile_completed',
    ];

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => Role::class,
        'profile_completed' => 'boolean',
    ];

    /**
     * Filament Panel Access
     */
    public function canAccessPanel(Panel $panel): bool
    {
        $panelId = $panel->getId();

        // 1) Selalu izinkan akses ke panel login supaya proses login tidak gagal
        //    (Filament perlu mengakses halaman login / proses login itu sendiri).
        if ($panelId === 'login') {
            return true;
        }

        // 2) Normalisasi role dari model (bisa string, int, atau Backed Enum)
        $role = $this->role;
        if ($role instanceof \BackedEnum) {
            // Enum bertipe backed (PHP 8.1+) — ambil value
            $roleValue = $role->value;
        } else {
            // Sudah string/integer — gunakan langsung
            $roleValue = $role;
        }

        // 3) Perbandingan panel -> role yang diizinkan
        return match ($panelId) {
            'admin' => $roleValue === Role::Admin->value,
            'karyawan' => $roleValue === Role::Karyawan->value,
            default => false,
        };
    }

    //     public function canAccessPanel(Panel $panel): bool
    // {
    //     // 1. Otorisasi untuk Panel Admin (misalnya, dengan ID 'admin')
    //     if ($panel->getId() === 'admin') {
    //         // Ganti 'isAdmin()' dengan metode atau properti yang benar untuk Role Admin Anda.
    //         // Contoh: $this->hasRole('Admin') jika menggunakan Spatie/RolesPermissions.
    //         // Kita larang akses jika user BUKAN Admin.
    //         return $this->hasRole('Admin');
    //     }

    //     // 2. Otorisasi untuk Panel Karyawan (misalnya, dengan ID 'karyawan')
    //     if ($panel->getId() === 'karyawan') {
    //         // Kita izinkan akses jika user adalah Karyawan.
    //         return $this->hasRole('Karyawan'); 
    //     }

    //     // Izinkan akses ke panel lain jika tidak ada batasan spesifik.
    //     return true; 
    // }

    /**
     * Relationships
     */

    public function testimonial()
{
        return $this->hasOne(Testimoni::class, 'user_id');
        // foreign key harus 'user_id' sesuai migration
}

    public function laporan()
    {
        return $this->hasMany(LaporanPenjualan::class, 'id_users');
    }

    public function verifikasiAdmin()
    {
        return $this->hasMany(Verifikasi::class, 'id_admin');
    }

    public function rekapHarians()
    {
        return $this->hasMany(RekapHarian::class, 'id_users');
    }
}
