<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
