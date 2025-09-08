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
     * The attributes that are mass assignable.
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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    /**
     * Get the user's role as a Role enum.
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
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */

    public function testimoni()
    {
        return $this->hasMany(Testimoni::class, 'id_users');
    }
}
