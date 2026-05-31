<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BahanBakuSeeder extends Seeder
{
    public function run(): void
    {
        $bahanBakus = [
            ['id' => 1, 'kode_bahan' => 'BB-001', 'nama' => 'Pisang', 'satuan' => 'sisir', 'harga_satuan' => 17000, 'stok' => 150, 'ss_bahan' => 20, 'rop_bahan' => 40],
            ['id' => 2, 'kode_bahan' => 'BB-002', 'nama' => 'Minyak Goreng', 'satuan' => 'liter', 'harga_satuan' => 23000, 'stok' => 100, 'ss_bahan' => 15, 'rop_bahan' => 30],
            ['id' => 3, 'kode_bahan' => 'BB-003', 'nama' => 'Gula', 'satuan' => 'kg', 'harga_satuan' => 19000, 'stok' => 25, 'ss_bahan' => 2, 'rop_bahan' => 5],
            ['id' => 4, 'kode_bahan' => 'BB-004', 'nama' => 'Garam', 'satuan' => 'kg', 'harga_satuan' => 12000, 'stok' => 10, 'ss_bahan' => 1, 'rop_bahan' => 2.5],

            ['id' => 5, 'kode_bahan' => 'BB-005', 'nama' => 'Bumbu Coklat M', 'satuan' => 'kg', 'harga_satuan' => 94300, 'stok' => 5, 'ss_bahan' => 0.5, 'rop_bahan' => 1.2],
            ['id' => 6, 'kode_bahan' => 'BB-006', 'nama' => 'Bumbu Coklat G', 'satuan' => 'kg', 'harga_satuan' => 73300, 'stok' => 5, 'ss_bahan' => 0.5, 'rop_bahan' => 1.2],
            ['id' => 7, 'kode_bahan' => 'BB-007', 'nama' => 'Bumbu Mentega', 'satuan' => 'kg', 'harga_satuan' => 71300, 'stok' => 4, 'ss_bahan' => 0.4, 'rop_bahan' => 1.0],
            ['id' => 8, 'kode_bahan' => 'BB-008', 'nama' => 'Bumbu Susu', 'satuan' => 'kg', 'harga_satuan' => 82300, 'stok' => 4, 'ss_bahan' => 0.4, 'rop_bahan' => 1.0],
            ['id' => 9, 'kode_bahan' => 'BB-009', 'nama' => 'Bumbu Garlic', 'satuan' => 'kg', 'harga_satuan' => 64300, 'stok' => 4, 'ss_bahan' => 0.4, 'rop_bahan' => 1.0],
            ['id' => 10, 'kode_bahan' => 'BB-010', 'nama' => 'Bumbu Garlic non msg', 'satuan' => 'kg', 'harga_satuan' => 87300, 'stok' => 3, 'ss_bahan' => 0.3, 'rop_bahan' => 0.8],
            ['id' => 11, 'kode_bahan' => 'BB-011', 'nama' => 'Bumbu Taliwang', 'satuan' => 'kg', 'harga_satuan' => 80000, 'stok' => 3, 'ss_bahan' => 0.3, 'rop_bahan' => 0.8],

            ['id' => 12, 'kode_bahan' => 'BB-012', 'nama' => 'Kemasan PCR Coklat', 'satuan' => 'pcs', 'harga_satuan' => 2150, 'stok' => 500, 'ss_bahan' => 50, 'rop_bahan' => 100],
            ['id' => 13, 'kode_bahan' => 'BB-013', 'nama' => 'Kemasan PCR Milky', 'satuan' => 'pcs', 'harga_satuan' => 2150, 'stok' => 500, 'ss_bahan' => 50, 'rop_bahan' => 100],
            ['id' => 14, 'kode_bahan' => 'BB-014', 'nama' => 'Kemasan PCR Garlic', 'satuan' => 'pcs', 'harga_satuan' => 2150, 'stok' => 500, 'ss_bahan' => 50, 'rop_bahan' => 100],
            ['id' => 15, 'kode_bahan' => 'BB-015', 'nama' => 'Kemasan PCR ay, Taliwang', 'satuan' => 'pcs', 'harga_satuan' => 2150, 'stok' => 500, 'ss_bahan' => 50, 'rop_bahan' => 100],
            ['id' => 16, 'kode_bahan' => 'BB-016', 'nama' => 'Kemasan pcm Coklat', 'satuan' => 'pcs', 'harga_satuan' => 1600, 'stok' => 400, 'ss_bahan' => 40, 'rop_bahan' => 80],
            ['id' => 17, 'kode_bahan' => 'BB-017', 'nama' => 'Kemasan pcm Milky', 'satuan' => 'pcs', 'harga_satuan' => 1600, 'stok' => 400, 'ss_bahan' => 40, 'rop_bahan' => 80],
        ];

        $now = now();
        foreach ($bahanBakus as $bahan) {
            $bahan['harga_updated_at'] = $now;
            $bahan['created_at'] = $now;
            $bahan['updated_at'] = $now;
            DB::table('bahan_baku')->insert($bahan);
        }
    }
}
