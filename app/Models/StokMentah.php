<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokMentah extends Model
{
    protected $table = 'stok_mentah';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'harga',
        'stok_awal',
        'stok_masuk',
        'stok_keluar',
        'stok_akhir',
        'harga_total_stok',
    ];

}
