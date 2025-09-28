<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    public $timestamps = true;

    protected $fillable = [
        'nama',
        'kode_produk',
        'harga',
        'image',
        'aktif',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'aktif' => 'boolean',
    ];

    public function detailLaporanPenjualan()
    {
        return $this->hasMany(DetailLaporanPenjualan::class, 'id_produk');
    }

    public function pergerakanStok()
    {
        return $this->hasMany(PergerakanStok::class, 'id_produk');
    }
    public function rekapHarians()
    {
        return $this->belongsToMany(RekapHarian::class, 'produk_rekap_harian')
                    ->withPivot('jumlah_terjual')
                    ->withTimestamps();
    }
     public function produkRekapHarians()
    {
        return $this->hasMany(ProdukRekapHarian::class, 'produk_id', 'id');
    }
}
