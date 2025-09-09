<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenjualan extends Model
{
    use HasFactory;

    protected $table = 'laporan_penjualan';

    protected $fillable = [
        'id_users',
        'tanggal_laporan',
        'total_produk_terjual',
        'omzet',
        'catatan',
        'status',
        'dikirim_pada',
        'disetujui_pada',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
        'omzet' => 'decimal:2',
        'dikirim_pada' => 'datetime',
        'disetujui_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function detailLaporan()
    {
        return $this->hasMany(DetailLaporanPenjualan::class, 'id_laporan');
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'id_laporan');
    }
}
