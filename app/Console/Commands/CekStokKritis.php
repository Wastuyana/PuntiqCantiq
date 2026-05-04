<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Produk;
use App\Notifications\StokKritisProduk;

#[Signature('stok:cek')]
#[Description('Mengecek stok produk yang kritis dan mengirim notifikasi')]
class CekStokKritis extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan stok...');

        // Ambil semua produk yang stoknya sudah di bawah limit
        $produks = Produk::whereRaw('stok <= safety_stok')->get();

        foreach ($produks as $produk) {
            $produk->cekStokKrisis();
        }

        $this->info('Pengecekan selesai! Notifikasi telah dikirim ke Owner.');
    }
}
