<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapHarian extends Model
{
    protected $fillable = [
        'id_users',
        'tanggal',
        'total_produk_terjual',
        'total_omzet',
        'produk_terlaris',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'produk_rekap_harian')
            ->withPivot('stok')
            ->withTimestamps();
    }

    public function ProdukRekapHarian()
    {
        return $this->hasMany(ProdukRekapHarian::class);
    }
}

