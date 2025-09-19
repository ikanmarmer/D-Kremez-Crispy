<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('testimoni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->string('avatar')->nullable();
            $table->decimal('rating', 2, 1)->unsigned();
            $table->text('content');
            $table->string('product_photo')->nullable();
            $table->string('status')->default('Menunggu');
            $table->boolean('is_notified')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('testimoni');
    }
};
