<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'pesan',
        'dibaca',
        'testimonial_id',
        'data'
    ];

    protected $casts = [
        'dibaca' => 'boolean',
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // PERBAIKAN: Relasi ke testimoni dengan nama tabel yang benar
    public function testimonial()
    {
        return $this->belongsTo(Testimoni::class, 'testimonial_id', 'id');
    }

    // Scope untuk notifikasi yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('dibaca', false);
    }

    // Scope untuk notifikasi user tertentu
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Method untuk menandai sebagai sudah dibaca
    public function markAsRead()
    {
        $this->update(['dibaca' => true]);
        return $this;
    }

    // TAMBAHAN: Method untuk format notifikasi dengan data testimoni
    public function toArray()
    {
        $array = parent::toArray();

        // Include testimonial data if available
        if ($this->testimonial) {
            $array['testimonial'] = [
                'id' => $this->testimonial->id,
                'content' => substr($this->testimonial->content, 0, 100) . '...',
                'rating' => $this->testimonial->rating,
                'status' => $this->testimonial->status
            ];
        }

        return $array;
    }
}
