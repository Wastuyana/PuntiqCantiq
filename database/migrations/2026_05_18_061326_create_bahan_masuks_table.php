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
        Schema::create('bahan_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('supplier');
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku');
            $table->date('tanggal_masuk');
            $table->decimal('jumlah_total', 15,2);
            $table->decimal('harga_beli', 12, 2);
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_masuk');
    }
};
