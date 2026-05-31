<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bahan_masuk', function (Blueprint $table) {
            // Mengubah kolom agar bisa bernilai NULL
            $table->string('kode_pesanan')->nullable()->change();
            $table->date('tanggal_pesan')->nullable()->change();
            $table->date('tanggal_masuk')->nullable()->change();
            $table->integer('jumlah_total')->nullable()->change();
            $table->string('status')->nullable()->change();
            
            // Kolom ini biasanya tidak perlu diubah jika sudah sesuai
            // tapi kita pastikan proses_pemesanan tetap memiliki default
            $table->string('proses_pemesanan')->default('di_pesan')->change();
        });
    }

    public function down()
    {
        
    }
};