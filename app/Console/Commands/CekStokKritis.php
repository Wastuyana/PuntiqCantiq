<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Produk;
use App\Services\ProductionService;

#[Signature('stok:cek')]
#[Description('Mengecek stok produk yang kritis dan mengirim notifikasi')]
class CekStokKritis extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ProductionService $service) // Inject servicenya di sini
    {
        $this->info('Memulai pengecekan stok...');

        $produks = Produk::whereRaw('stok <= ss_produk')->get();

        foreach ($produks as $produk) {
            $service->cekStokKritis($produk);
        }

        $this->info('Pengecekan selesai!');
    }
}
