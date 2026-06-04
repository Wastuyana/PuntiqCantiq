<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\BahanBaku;
use App\Services\ProductionService;

#[Signature('stokBahan:hitung-ss')]
#[Description('Menghitung ulang safety stock dan batas minimal stok seluruh produk berdasarkan tren penjualan 30 hari')]
class HitungSafetyStockBahan extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ProductionService $service)
    {
        $this->info('Memulai kalkulasi ulang Safety Stock dan Batas Minimal Stok...');

        // Ambil semua produk yang aktif/ada di database
        $bahans = BahanBaku::all();

        $kondisiSelesai = 0;
        $kondisiKosong = 0;

        foreach ($bahans as $bahan) {
            // Panggil fungsi yang kamu buat di dalam ProductionService
            $result = $service->updateSafetyStockBahan($bahan);

            if ($result) {
                $this->line("-> Berhasil memperbarui SS & ROP untuk bahan: {$bahan->nama}");
                $kondisiSelesai++;
            } else {
                $this->warn("-> Melewati bahan: {$bahan->nama} (Tidak ada data penjualan 30 hari terakhir)");
                $kondisiKosong++;
            }
        }

        $this->info("Kalkulasi selesai! Berhasil: {$kondisiSelesai}, Dilewati: {$kondisiKosong}.");
    }
}