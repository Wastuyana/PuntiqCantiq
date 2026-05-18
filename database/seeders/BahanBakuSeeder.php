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
        \App\Models\BahanBaku::create(['kode_bahan' => 'BB-001', 'nama' => 'Pisang', 'satuan' => 'sisir', 'harga_satuan' => 20000, 'stok' => 100, 'ss_bahan' => 0, 'rop_bahan' => 0]);
        \App\Models\BahanBaku::create(['kode_bahan' => 'B-002', 'nama' => 'Minyak Goreng', 'satuan' => 'liter', 'harga_satuan' => 18000, 'stok' => 20]);
        \App\Models\BahanBaku::create(['kode_bahan' => 'B-003', 'nama' => 'Cokelat Bubuk', 'satuan' => 'kg', 'harga_satuan' => 50000, 'stok' => 10]);
        \App\Models\BahanBaku::create(['kode_bahan' => 'B-004', 'nama' => 'Garam', 'satuan' => 'kg', 'harga_satuan' => 16000, 'stok' => 1]);
        \App\Models\BahanBaku::create(['kode_bahan' => 'B-005', 'nama' => 'Kemasan', 'satuan' => 'pcs', 'harga_satuan' => 2000, 'stok' => 1000]);
    }
}
