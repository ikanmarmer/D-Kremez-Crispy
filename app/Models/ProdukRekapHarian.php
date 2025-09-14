<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukRekapHarian extends Model
{
    protected $table = 'produk_rekap_harian';

    protected $fillable = [
        'id_produk',
        'id_rekap_harian',
        'stok',
    ];

    public function rekapHarian()
    {
        return $this->belongsTo(RekapHarian::class, 'rekap_harian_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
