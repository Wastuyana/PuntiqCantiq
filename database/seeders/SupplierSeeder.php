<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\BahanBaku;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $supplier = [
            ['kode' => 'SPL-001', 'nama' => 'PT Pangan', 'alamat' => 'Jl. Industri No. 1, Surabaya', 'hp' => '081234567890'],
            ['kode' => 'SPL-002', 'nama' => 'CV Kemasan', 'alamat' => 'Jl. Packaging Raya No. 5, Sidoarjo', 'hp' => '085765432109'],
            ['kode' => 'SPL-003', 'nama' => 'Toko Kue', 'alamat' => 'Pasar Modern No. 12, Surabaya', 'hp' => '081122334455'],
            ['kode' => 'SPL-004', 'nama' => 'UD Berkah', 'alamat' => 'Jl. Pertanian No. 8, Gresik', 'hp' => '089988776655'],
            ['kode' => 'SPL-005', 'nama' => 'CV Mitra', 'alamat' => 'Jl. Melati No. 20, Mojokerto', 'hp' => '082233445566'],
        ];

        foreach ($supplier as $s) {
            $supplier = Supplier::updateOrCreate(
                ['kode_supplier' => $s['kode']],
                [
                    'nama_supplier'   => $s['nama'],
                    'alamat_supplier' => $s['alamat'],
                    'no_hp'           => $s['hp'],
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]
            );

            // 2. Hubungkan dengan Bahan Baku berdasarkan ID
            // Contoh logika: 
            if ($s['kode'] == 'SPL-001') {
                // Minyak (ID 2)
                $supplier->bahanBaku()->sync([2]);
            } elseif ($s['kode'] == 'SPL-002') {
                // Kemasan PCR (ID 13, 14, 15)
                $supplier->bahanBaku()->sync([13, 14, 15]);
            } elseif ($s['kode'] == 'SPL-003') {
                // Coklat (ID 5, 6, 7)
                $supplier->bahanBaku()->sync([5, 6, 7]);
            } elseif ($s['kode'] == 'SPL-004') {
                // Pisang (ID 1)
                $supplier->bahanBaku()->sync([1]);
            } elseif ($s['kode'] == 'SPL-005') {
                // Contoh bahan lain (ID 8, 9)
                $supplier->bahanBaku()->sync([8, 9]);
            }
        }
    }
}