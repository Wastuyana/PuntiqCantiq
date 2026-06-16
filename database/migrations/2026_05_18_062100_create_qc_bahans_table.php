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
        Schema::create('qc_bahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_masuk_id')->constrained('bahan_masuk');
            $table->decimal('jumlah_bagus', 15,2);
            $table->decimal('jumlah_rusak', 15,2);
            $table->text('catatan')->nullable();
            $table->date('tanggal_qc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qc_bahan');
    }
};
