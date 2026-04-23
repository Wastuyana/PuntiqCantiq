<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batch_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')
                ->constrained('batch')
                ->onDelete('cascade');
            $table->foreignId('produk_id')
                ->constrained('produk')->onDelete('cascade');
            $table->integer('hasil_target');
            $table->integer('hasil_aktual')->default(0);
            $table->decimal('hpp_aktual', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_hasil');
    }
};
