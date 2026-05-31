<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bahan_masuk', function (Blueprint $table) {
            $table->string('kode_pesanan')->after('id')->nullable();
            $table->string('proses_pemesanan')->default('di_pesan')->after('kode_pesanan');
            $table->integer('jumlah_pesan')->after('kode_pesanan')->nullable();

        });
    }

    public function down()
    {
        Schema::table('bahan_masuk', function (Blueprint $table) {
            $table->dropColumn(['kode_pesanan', 'proses_pemesanan', 'jumlah_pesan']);
        });
    }
};