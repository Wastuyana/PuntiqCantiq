<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('produk')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $products = [
            // === GOLONGAN REGULER (PCR / POR) ===
            [
                'id' => 1,
                'kode_produk' => 'PCR-OR', // Kode dasar PCR / POR Original
                'kategori' => 'Keripik Pisang',
                'varian' => 'Original',
                'ukuran' => '90g',
                'est_biaya_tenaga' => 1500,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 9500,
                'harga_jual' => 18000
            ],
            [
                'id' => 2,
                'kode_produk' => 'PCR-CK', // PCR Coklat
                'kategori' => 'Keripik Pisang',
                'varian' => 'Coklat',
                'ukuran' => '85g',
                'est_biaya_tenaga' => 1800,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 11500,
                'harga_jual' => 19500
            ],
            [
                'id' => 3,
                'kode_produk' => 'PCR-MB', // PCR Milky Butter
                'kategori' => 'Keripik Pisang',
                'varian' => 'Milky Butter',
                'ukuran' => '85g',
                'est_biaya_tenaga' => 1800,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 11200,
                'harga_jual' => 18900
            ],
            [
                'id' => 4,
                'kode_produk' => 'PCR-GR', // PCR Garlic
                'kategori' => 'Keripik Pisang',
                'varian' => 'Garlic',
                'ukuran' => '90g',
                'est_biaya_tenaga' => 1500,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 9800,
                'harga_jual' => 18000
            ],
            [
                'id' => 5,
                'kode_produk' => 'PCR-TL', // PCR Taliwang
                'kategori' => 'Keripik Pisang',
                'varian' => 'Ayam Taliwang',
                'ukuran' => '65g',
                'est_biaya_tenaga' => 1600,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 10200,
                'harga_jual' => 18900
            ],

            // === GOLONGAN MINI (PCM) ===
            [
                'id' => 6,
                'kode_produk' => 'PCM-CK', // PCM Coklat Small
                'kategori' => 'Keripik Pisang Mini',
                'varian' => 'Coklat Small',
                'ukuran' => '40g',
                'est_biaya_tenaga' => 1000,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 6500,
                'harga_jual' => 11000
            ],
            [
                'id' => 7,
                'kode_produk' => 'PCM-MB', // PCM Milky Butter Small
                'kategori' => 'Keripik Pisang Mini',
                'varian' => 'Milky Butter Small',
                'ukuran' => '40g',
                'est_biaya_tenaga' => 1000,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 6200,
                'harga_jual' => 11000
            ],

            // === GOLONGAN CHOCO FLAKES ===
            [
                'id' => 8,
                'kode_produk' => 'CBF-TPL',
                'kategori' => 'Choco Banana Flakes',
                'varian' => 'Toples',
                'ukuran' => '180g',
                'est_biaya_tenaga' => 2500,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 21000,
                'harga_jual' => 35000
            ],
            [
                'id' => 9,
                'kode_produk' => 'CBF-BIG',
                'kategori' => 'Choco Banana Flakes',
                'varian' => 'Big Pack',
                'ukuran' => '500g',
                'est_biaya_tenaga' => 5000,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 45000,
                'harga_jual' => 75000
            ],

            // === GOLONGAN BIG PACK 500G (BP) ===
            [
                'id' => 10,
                'kode_produk' => 'BP-OR500',
                'kategori' => 'Keripik Pisang Big Pack',
                'varian' => 'Original Big',
                'ukuran' => '500g',
                'est_biaya_tenaga' => 4000,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 38000,
                'harga_jual' => 65000
            ],
            [
                'id' => 11,
                'kode_produk' => 'BP-CK500',
                'kategori' => 'Keripik Pisang Big Pack',
                'varian' => 'Coklat Big',
                'ukuran' => '500g',
                'est_biaya_tenaga' => 4500,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 43000,
                'harga_jual' => 77000
            ],
            [
                'id' => 12,
                'kode_produk' => 'BP-MB500',
                'kategori' => 'Keripik Pisang Big Pack',
                'varian' => 'Milky Butter Big',
                'ukuran' => '500g',
                'est_biaya_tenaga' => 4500,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 42000,
                'harga_jual' => 77000
            ],
            [
                'id' => 13,
                'kode_produk' => 'BP-GR500',
                'kategori' => 'Keripik Pisang Big Pack',
                'varian' => 'Garlic Big',
                'ukuran' => '500g',
                'est_biaya_tenaga' => 4000,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 39000,
                'harga_jual' => 65000
            ],
            [
                'id' => 14,
                'kode_produk' => 'BP-TL500',
                'kategori' => 'Keripik Pisang Big Pack',
                'varian' => 'Ayam Taliwang Big',
                'ukuran' => '500g',
                'est_biaya_tenaga' => 4200,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 40000,
                'harga_jual' => 65000
            ],

            // === GOLONGAN BIG PACK 250G (BP) ===
            [
                'id' => 15,
                'kode_produk' => 'BP-OR250',
                'kategori' => 'Keripik Pisang Big Pack',
                'varian' => 'Original Medium',
                'ukuran' => '250g',
                'est_biaya_tenaga' => 2500,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 20000,
                'harga_jual' => 35000
            ],
            [
                'id' => 16,
                'kode_produk' => 'BP-CK250',
                'kategori' => 'Keripik Pisang Big Pack',
                'varian' => 'Coklat Medium',
                'ukuran' => '250g',
                'est_biaya_tenaga' => 2800,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 22500,
                'harga_jual' => 35000
            ],
            [
                'id' => 17,
                'kode_produk' => 'BP-MB250',
                'kategori' => 'Keripik Pisang Big Pack',
                'varian' => 'Milky Butter Medium',
                'ukuran' => '250g',
                'est_biaya_tenaga' => 2800,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 22000,
                'harga_jual' => 35000
            ],
            [
                'id' => 18,
                'kode_produk' => 'BP-GR250',
                'kategori' => 'Keripik Pisang Big Pack',
                'varian' => 'Garlic Medium',
                'ukuran' => '250g',
                'est_biaya_tenaga' => 2500,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 21000,
                'harga_jual' => 35000
            ],
            [
                'id' => 19,
                'kode_produk' => 'BP-TL250',
                'kategori' => 'Keripik Pisang Big Pack',
                'varian' => 'Ayam Taliwang Medium',
                'ukuran' => '250g',
                'est_biaya_tenaga' => 2600,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 21500,
                'harga_jual' => 35000
            ],

            // === GOLONGAN CRISPY JAR (BCJ) ===
            [
                'id' => 20,
                'kode_produk' => 'BCJ-CK',
                'kategori' => 'Banana Crispy Jar',
                'varian' => 'Coklat Jar',
                'ukuran' => 'Standard',
                'est_biaya_tenaga' => 1500,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 8500,
                'harga_jual' => 13500
            ],
            [
                'id' => 21,
                'kode_produk' => 'BCJ-TR',
                'kategori' => 'Banana Crispy Jar',
                'varian' => 'Tiramisu Jar',
                'ukuran' => 'Standard',
                'est_biaya_tenaga' => 1500,
                'est_biaya_overhead' => 10,
                'hpp_standar' => 8800,
                'harga_jual' => 13500
            ],
        ];

        foreach ($products as $prod) {
            DB::table('produk')->insert([
                'id'                 => $prod['id'],
                'kode_produk'        => $prod['kode_produk'],
                'kategori'           => $prod['kategori'],
                'varian'             => $prod['varian'],
                'ukuran'             => $prod['ukuran'],
                'stok'               => 0,
                'stok_mitra'         => 0,
                'ss_produk'          => 0,
                'rop_produk'         => 0,
                'est_biaya_tenaga'   => $prod['est_biaya_tenaga'],
                'est_biaya_overhead' => $prod['est_biaya_overhead'],
                'hpp_standar'        => $prod['hpp_standar'],
                'harga_jual'         => $prod['harga_jual'],
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }
    }
}
