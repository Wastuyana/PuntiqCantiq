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
        Schema::create('batch_bahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')
                ->constrained('batch')
                ->onDelete('cascade');
            $table->foreignId('bahan_baku_id')
                ->constrained('bahan_baku');
            $table->decimal('bahan_target', 15, 2);
            $table->decimal('bahan_aktual', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_bahan');
    }
};
