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
        Schema::table('penjualan', function (Blueprint $table) {
            $table->string('status_customer')->default('pelanggan')->after('subtotal_harga');             
            $table->unsignedBigInteger('pelanggan_id')->nullable()->after('status_customer');
            $table->unsignedBigInteger('mitra_id')->nullable()->after('pelanggan_id');
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'qris', 'hutang'])->after('pelanggan_id');

            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onDelete('set null');
            $table->foreign('mitra_id')->references('id')->on('mitra')->onDelete('set null');
        });
    }

public function down(): void
{
    Schema::table('penjualan', function (Blueprint $table) {
        $table->dropForeign(['pelanggan_id']);
        $table->dropForeign(['mitra_id']);

        $table->dropColumn(['status_customer', 'pelanggan_id', 'mitra_id', 'metode_pembayaran']);
    });
}
};