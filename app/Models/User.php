<?php

namespace App\Models;

use App\Enums\Role;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'profile_completed' => 'boolean',
        ];
    }

    /**
     * Filament Panel Access
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->role === Role::Admin,
            'owner' => $this->role === Role::Owner,
            'karyawan' => $this->role === Role::Karyawan,
            default => false,
        };
    }

    /**
     * Relationships
     */
    public function testimoni()
    {
        return $this->hasMany(Testimoni::class, 'id_users');
    }

    public function laporan()
    {
        return $this->hasMany(LaporanPenjualan::class, 'id_pengguna');
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
