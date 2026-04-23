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
        Schema::create('penyesuaian_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')
                ->constrained('produk')
                ->onDelete('cascade');
            $table->foreignId('batch_id')
                ->constrained('batch')
                ->onDelete('cascade');
            $table->integer('jumlah_rusak');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyesuaian_stok');
    }
};
