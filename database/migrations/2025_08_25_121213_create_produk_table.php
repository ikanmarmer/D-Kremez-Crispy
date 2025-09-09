<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->decimal('harga', 12, 2);
            $table->integer('stok')->default(0);
            $table->string('image')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            $table->boolean('aktif')->default(true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('produk');
    }
};
