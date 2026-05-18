<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bom;
use App\Models\Produk;
use App\Models\BahanBaku;

class BomSeeder extends Seeder
{
    public function run()
    {
        // Ambil data bahan baku untuk mendapatkan ID-nya
        $pisang = BahanBaku::where('nama', 'Pisang')->first()->id;
        $minyak = BahanBaku::where('nama', 'Minyak Goreng')->first()->id;
        $coklat = BahanBaku::where('nama', 'Cokelat Bubuk')->first()->id;
        $garam = BahanBaku::where('nama', 'Garam')->first()->id;
        $kemasan = BahanBaku::where('nama', 'Kemasan')->first()->id;

        $semuaProduk = Produk::all();

        foreach ($semuaProduk as $produk) {
            // 1. Ambil angka berat dari ukuran (misal "250gr" -> 250)
            $berat = (int) filter_var($produk->ukuran, FILTER_SANITIZE_NUMBER_INT);

            // Rasio kebutuhan pisang (asumsi: 1 sisir pisang = 500gr keripik)
            $kebutuhanPisang = $berat / 500;

            // Rasio minyak (asumsi: 0.1 liter per 250gr keripik)
            $kebutuhanMinyak = ($berat / 250) * 0.1;

            // --- Bahan Dasar (Wajib untuk semua) ---

            // Pisang
            Bom::create([
                'produk_id' => $produk->id,
                'bahan_baku_id' => $pisang,
                'jumlah_kebutuhan' => $kebutuhanPisang
            ]);

            // Minyak Goreng
            Bom::create([
                'produk_id' => $produk->id,
                'bahan_baku_id' => $minyak,
                'jumlah_kebutuhan' => $kebutuhanMinyak
            ]);

            Bom::create([
                'produk_id' => $produk->id,
                'bahan_baku_id' => $garam,
                'jumlah_kebutuhan' => 0.005
            ]);

            Bom::create([
                'produk_id' => $produk->id,
                'bahan_baku_id' => $kemasan,
                'jumlah_kebutuhan' => 1
            ]);

            if (str_contains(strtolower($produk->varian), 'coklat') || str_contains(strtolower($produk->kategori), 'choco')) {
                Bom::create([
                    'produk_id' => $produk->id,
                    'bahan_baku_id' => $coklat,
                    'jumlah_kebutuhan' => ($berat / 250) * 0.05 // misal 0.05kg coklat per 250gr
                ]);
            }
        }
    }
}
