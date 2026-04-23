<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Produk;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $produks = Produk::all();

        if ($produks->isEmpty()) {
            $this->command->info("Data produk kosong, isi produk dulu ya!");
            return;
        }

        // data 30 hari ke belakang
        for ($i = 0; $i < 30; $i++) {
            // Asumsi dalam 1 hari ada 1-3 transaksi
            $jumlahTransaksiPerHari = rand(1, 3);

            for ($j = 0; $j < $jumlahTransaksiPerHari; $j++) {
                $tanggal = Carbon::now()->subDays($i)->setHour(rand(8, 20));
                
                // 1. Simpan Header Penjualan
                $penjualan = Penjualan::create([
                    'tanggal_penj'   => $tanggal,
                    'total_prod'     => 0, // Akan diupdate setelah detail masuk
                    'subtotal_harga' => 0, // Akan diupdate setelah detail masuk
                ]);

                $totalQtyTransaksi = 0;
                $totalHargaTransaksi = 0;

                // 2. Simpan Detail (Satu transaksi beli 1-2 jenis produk)
                $produkAcak = $produks->random(rand(1, 2));

                foreach ($produkAcak as $produk) {
                    $qty = rand(2, 10); // Random terjual 2-10 pcs per item
                    $subtotal = $qty * $produk->harga_jual;

                    DetailPenjualan::create([
                        'penjualan_id'  => $penjualan->id,
                        'produk_id'     => $produk->id,
                        'jumlah_produk' => $qty,
                        'total_harga'   => $subtotal,
                    ]);

                    $totalQtyTransaksi += $qty;
                    $totalHargaTransaksi += $subtotal;
                }

                // 3. Update kembali Header dengan total yang benar
                $penjualan->update([
                    'total_prod'     => $totalQtyTransaksi,
                    'subtotal_harga' => $totalHargaTransaksi,
                ]);
            }
        }
    }
}