<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bahan_masuk', function (Blueprint $table) {
           
            $table->string('kode_pesanan')->nullable()->change();
            $table->date('tanggal_pesan')->nullable()->change();
            $table->date('tanggal_masuk')->nullable()->change();
            $table->decimal('jumlah_total', 15,2)->nullable()->change();
            $table->string('status')->nullable()->change();
            
            
            $table->string('proses_pemesanan')->default('di_pesan')->change();
        });
    }

    public function down()
    {
        
    }
};