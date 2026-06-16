<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FasilitasCheck;

class FasilitasCheckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FasilitasCheck::create([
            'slug' => 'area_produksi',
            'komponen' => 'Area Produksi',
            'deskripsi' => 'Ventilasi, pencahayaan, & kebersihan lantai',
            'status' => 'sesuai',
            'tanggal_cek' => now()->subDays(2)
        ]);

        FasilitasCheck::create([
            'slug' => 'alat_produksi',
            'komponen' => 'Alat Produksi',
            'deskripsi' => 'Material stainless steel & sterilitas alat',
            'status' => 'sesuai',
            'tanggal_cek' => now()->subDays(5)
        ]);

        FasilitasCheck::create([
            'slug' => 'limbah',
            'komponen' => 'Penanganan Limbah',
            'deskripsi' => 'Pimisahan limbah organik & non-organik',
            'status' => 'perbaikan',
            'tanggal_cek' => now()
        ]);
    }
}
