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
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,HasApiTokens, Notifiable;

    /**
     *
     * @var list<string>
     */

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'remember_token',
        'password',
    ];


    /**
     * Get the attributes that should be cast.
    *
    * @return array<string, string>
    */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    /**
     *
     * @return array<string, string>
     */

    /**
     *
     * @return Role
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
     *
     * @var list<string>
    */

    public function testimoni()
    {
        return $this->hasMany(Testimoni::class, 'id_users');
    }
    // relationships
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
