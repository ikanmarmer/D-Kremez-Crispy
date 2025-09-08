<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_users')->constrained('users');
            $table->date('tanggal_laporan');
            $table->string('total_produk_terjual');
            $table->decimal('omzet', 14, 2);
            $table->enum('status', ['draf','dikirim','ditandai', 'disetujui', 'ditolak'])->default('draf');
            $table->timestamp('dikirim_pada')->useCurrent();
            $table->timestamp('disetujui_pada')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_penjualan');
    }
};
