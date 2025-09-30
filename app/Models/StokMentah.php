<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokMentah extends Model
{
    protected $table = 'stok_mentahs';

    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'nama',
        'harga',
        'jumlah',
        'harga_total_stok',
    ];

}
