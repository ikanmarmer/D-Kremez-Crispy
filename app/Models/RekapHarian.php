<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapHarian extends Model
{
    protected $table = 'rekap_harians';

    protected $fillable = [
        'tanggal_rekap',
        'jumlah_produk_terjual',
        'total_omzet',
    ];

    public $timestamps = false;
    
    protected $casts = [
        'tanggal_rekap' => 'date',
        'total_omzet' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
