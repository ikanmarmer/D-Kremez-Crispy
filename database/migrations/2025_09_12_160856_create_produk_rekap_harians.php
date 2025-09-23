<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produk_rekap_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekap_harian_id')->constrained('rekap_harians')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->integer('jumlah_terjual')->default(0);
            $table->integer('sisa_stok')->default(0);
            $table->decimal('subtotal_omzet', 14, 2)->default(0);
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_rekap_harians');
    }
};
