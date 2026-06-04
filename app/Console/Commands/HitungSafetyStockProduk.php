<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Produk;
use App\Services\ProductionService;

#[Signature('stok:hitung-ss')]
#[Description('Menghitung ulang safety stock dan batas minimal stok seluruh produk berdasarkan tren penjualan 30 hari')]
class HitungSafetyStockProduk extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ProductionService $service)
    {
        $this->info('Memulai kalkulasi ulang Safety Stock dan Batas Minimal Stok...');

        // Ambil semua produk yang aktif/ada di database
        $produks = Produk::all();

        $kondisiSelesai = 0;
        $kondisiKosong = 0;

        foreach ($produks as $produk) {
            // Panggil fungsi yang kamu buat di dalam ProductionService
            $result = $service->updateSafetyStockProduk($produk);

            if ($result) {
                $this->line("-> Berhasil memperbarui SS & ROP untuk produk: {$produk->kode_produk}");
                $kondisiSelesai++;
            } else {
                $this->warn("-> Melewati produk: {$produk->kode_produk} (Tidak ada data penjualan 30 hari terakhir)");
                $kondisiKosong++;
            }
        }

        $this->info("Kalkulasi selesai! Berhasil: {$kondisiSelesai}, Dilewati: {$kondisiKosong}.");
    }
}