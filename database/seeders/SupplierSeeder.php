<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'kode_supplier' => 'SPL-001', 
                'nama_supplier' => 'PT Pangan', 
                'alamat_supplier' => 'Jl. Industri No. 1, Surabaya', 
                'nama_bb' => 'Minyak', 
                'no_hp' => '081234567890'
            ],
            [
                'kode_supplier' => 'SPL-002', 
                'nama_supplier' => 'CV Kemasan', 
                'alamat_supplier' => 'Jl. Packaging Raya No. 5, Sidoarjo', 
                'nama_bb' => 'Kemasan PCR', 
                'no_hp' => '085765432109'
            ],
            [
                'kode_supplier' => 'SPL-003', 
                'nama_supplier' => 'Toko Kue', 
                'alamat_supplier' => 'Pasar Modern No. 12, Surabaya', 
                'nama_bb' => 'Coklat', 
                'no_hp' => '081122334455'
            ],
            [
                'kode_supplier' => 'SPL-004', 
                'nama_supplier' => 'UD Berkah', 
                'alamat_supplier' => 'Jl. Pertanian No. 8, Gresik', 
                'nama_bb' => 'Pisang', 
                'no_hp' => '089988776655'
            ],
            [
                'kode_supplier' => 'SPL-005', 
                'nama_supplier' => 'CV Mitra', 
                'alamat_supplier' => 'Jl. Melati No. 20, Mojokerto', 
                'nama_bb' => 'Rice Crispy', 
                'no_hp' => '082233445566'
            ],
        ];

        foreach ($suppliers as $s) {
            DB::table('supplier')->updateOrInsert(
                ['kode_supplier' => $s['kode_supplier']], 
                [
                    'nama_supplier'   => $s['nama_supplier'],
                    'alamat_supplier' => $s['alamat_supplier'],
                    'nama_bb'         => $s['nama_bb'],
                    'no_hp'           => $s['no_hp'],
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]
            );
        }
    }
}