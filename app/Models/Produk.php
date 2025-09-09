<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'nama',
        'kode_produk',
        'harga',
        'stok',
        'image',
        'aktif',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'aktif' => 'boolean',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];
    
    public function pergerakanStok()
    {
        return $this->hasMany(PergerakanStok::class, 'id_produk');
    }
}
