<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Testimoni extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'testimoni';

    protected $fillable = [
        'user_id',
        'name',
        'avatar',
        'rating',
        'content',
        'product_photo',
        'status',
        'is_notified',
        'admin_feedback'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_notified' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Scope untuk testimoni yang disetujui
    public function scopeApproved($query)
    {
        return $query->where('status', 'disetujui');
    }

    // Scope untuk testimoni yang ditolak
    public function scopeRejected($query)
    {
        return $query->where('status', 'ditolak');
    }

    // Scope untuk testimoni yang menunggu
    public function scopePending($query)
    {
        return $query->where('status', 'menunggu');
    }
}
