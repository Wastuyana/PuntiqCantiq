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
        Schema::create('batch', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_batch')->unique();
            $table->date('tanggal_produksi');
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->enum('status', ['draft', 'selesai'])->default('draft');
            $table->tinyInteger('checklist_sop')->default(0);
            $table->text('sop_details')->nullable();
            $table->decimal('biaya_bahan', 15, 2)->default(0);
            $table->decimal('biaya_tenagakerja', 15, 2)->default(0);
            $table->decimal('biaya_overhead', 15, 2)->default(0);
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch');
    }
};
