<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['kategori' => 'Keripik Pisang', 'varian' => 'Original', 'ukuran' => '250gr', 'stok' => 368, 'est_biaya_tenaga' => 3500, 'est_biaya_overhead' => 10.00, 'harga_jual' => 18000],
            ['kategori' => 'Keripik Pisang', 'varian' => 'Original', 'ukuran' => '90 gr', 'stok' => 228, 'est_biaya_tenaga' => 2000, 'est_biaya_overhead' => 10.00, 'harga_jual' => 11000],
            ['kategori' => 'Keripik Pisang', 'varian' => 'Original', 'ukuran' => '500 gr', 'stok' => 35, 'est_biaya_tenaga' => 4000, 'est_biaya_overhead' => 10.00, 'harga_jual' => 38000],
            ['kategori' => 'Banana Crispy Jar', 'varian' => 'Coklat', 'ukuran' => '200 ml', 'stok' => 164, 'est_biaya_tenaga' => 3000, 'est_biaya_overhead' => 10.00, 'harga_jual' => 13000],
            ['kategori' => 'Keripik Pisang', 'varian' => 'Coklat', 'ukuran' => '250gr', 'stok' => 248, 'est_biaya_tenaga' => 3400, 'est_biaya_overhead' => 10.00, 'harga_jual' => 18900],
            ['kategori' => 'Banana Crispy Jar', 'varian' => 'Tiramisu', 'ukuran' => '200 ml', 'stok' => 50, 'est_biaya_tenaga' => 3500, 'est_biaya_overhead' => 10.00, 'harga_jual' => 13000],
            ['kategori' => 'Choco Banana Flakes', 'varian' => 'Coklat', 'ukuran' => '180 gr', 'stok' => 146, 'est_biaya_tenaga' => 4900, 'est_biaya_overhead' => 10.00, 'harga_jual' => 35000],
            ['kategori' => 'Keripik Pisang', 'varian' => 'Coklat', 'ukuran' => '500 gr', 'stok' => 100, 'est_biaya_tenaga' => 4500, 'est_biaya_overhead' => 10.00, 'harga_jual' => 77000],
            ['kategori' => 'Keripik Pisang', 'varian' => 'Milky Butter', 'ukuran' => '250gr', 'stok' => 45, 'est_biaya_tenaga' => 3500, 'est_biaya_overhead' => 10.00, 'harga_jual' => 18900],
        ];

        foreach ($data as $item) {
            Produk::create([
                'kategori' => $item['kategori'],
                'varian' => $item['varian'],
                'ukuran' => $item['ukuran'],
                'stok' => $item['stok'],
                'safety_stok' => 0, 
                'est_biaya_tenaga' => $item['est_biaya_tenaga'],
                'est_biaya_overhead' => $item['est_biaya_overhead'],
                'hpp_standar' => 0, 
                'harga_jual' => $item['harga_jual'],
            ]);
        }
    }
}
