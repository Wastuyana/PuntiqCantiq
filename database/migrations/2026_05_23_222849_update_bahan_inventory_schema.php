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
       Schema::table('bahan_masuk', function (Blueprint $table) {
        $table->date('tanggal_pesan')->nullable()->after('tanggal_masuk');
        });

        Schema::table('bahan_baku', function (Blueprint $table) {
            $table->timestamp('harga_updated_at')->nullable()->after('harga_satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('bahan_masuk', function (Blueprint $table) { $table->dropColumn('tanggal_pesan'); });
    Schema::table('bahan_baku', function (Blueprint $table) { $table->dropColumn(['harga_updated_at']); });
    }
};
