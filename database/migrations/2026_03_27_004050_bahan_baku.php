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
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id('id');
            $table->string('kode_bahan', 20)->unique();
            $table->string('nama', 250);
            $table->string('satuan', 150);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('stok', 15,2)->default(0);
            $table->integer('ss_bahan')->default(0);
            $table->integer('rop_bahan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};
