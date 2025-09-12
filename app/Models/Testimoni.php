<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    protected $table = 'testimoni';

    protected $fillable = [
        'user_id',
        'komentar',
        'rating',
        'status',
        'umpan_balik_admin'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk testimoni yang disetujui
    public function scopeApproved($query)
    {
        return $query->where('status', 'disetujui');
    }

    // Scope untuk testimoni yang menunggu
    public function scopePending($query)
    {
        return $query->where('status', 'menunggu');
    }

    // Cek apakah user sudah memberikan testimoni
    public static function userHasTestimonial($userId)
    {
        return static::where('user_id', $userId)->exists();
    }
}
