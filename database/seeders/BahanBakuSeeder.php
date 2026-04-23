<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\BahanBaku::create(['nama' => 'Pisang', 'satuan' => 'sisir', 'harga_satuan' => 20000, 'stok' => 50]);
        \App\Models\BahanBaku::create(['nama' => 'Minyak Goreng', 'satuan' => 'liter', 'harga_satuan' => 18000, 'stok' => 20]);
        \App\Models\BahanBaku::create(['nama' => 'Cokelat Bubuk', 'satuan' => 'kg', 'harga_satuan' => 50000, 'stok' => 10]);
        \App\Models\BahanBaku::create(['nama' => 'Garam', 'satuan' => 'kg', 'harga_satuan' => 16000, 'stok' => 1]);
    }
}
