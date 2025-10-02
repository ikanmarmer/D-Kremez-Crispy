<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Testimoni extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'testimoni';

    protected $fillable = [
        'user_id', // DIUBAH: dari id_users menjadi user_id
        'name',
        'avatar',
        'rating',
        'content',
        'product_photo',
        'status',
        'is_notified',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'is_notified' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        // DIUBAH: foreign key dari id_users menjadi user_id
        return $this->belongsTo(User::class, 'user_id')->withDefault([
            'name' => 'User Tidak Ditemukan',
            'avatar' => null
        ]);
    }

    // Relasi ke notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'testimonial_id');
    }

    // Scope untuk testimoni yang disetujui
    public function scopeApproved($query)
    {
        return $query->where('status', Status::Disetujui->value);
    }

    // Scope untuk testimoni yang ditolak
    public function scopeRejected($query)
    {
        return $query->where('status', Status::Ditolak->value);
    }

    // Scope untuk testimoni yang menunggu
    public function scopePending($query)
    {
        return $query->where('status', Status::Menunggu->value);
    }
}
