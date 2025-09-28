<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukRekapHarian extends Model
{
    protected $table = 'produk_rekap_harians';

    protected $fillable = [
        'rekap_harian_id',
        'produk_id',
        'jumlah_terjual',
    ];

    public function rekapHarian()
    {
        return $this->belongsTo(RekapHarian::class, 'rekap_harian_id', 'id');
    }


    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
