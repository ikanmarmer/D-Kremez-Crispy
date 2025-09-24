<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // testimonial_approved, testimonial_rejected, dll
            $table->text('pesan');
            $table->boolean('dibaca')->default(false);
            // PERBAIKAN: Pastikan nama tabel sesuai dengan model Testimoni
            $table->foreignId('testimonial_id')->nullable()->constrained('testimoni')->onDelete('cascade');
            $table->json('data')->nullable(); // data tambahan
            $table->timestamps();

            // Index untuk performa
            $table->index(['user_id', 'dibaca']);
            $table->index(['type', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
