<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapHarian extends Model
{
    protected $table = 'rekap_harians';

    protected $fillable = [
        'id_users',
        'tanggal_rekap',
        'jumlah_produk_terjual',
        'total_omzet',
        'stok_tersedia',
    ];

    public $timestamps = true;

    protected $casts = [
        'tanggal_rekap' => 'date',
        'total_omzet' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
