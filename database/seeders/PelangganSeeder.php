<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelanggans = [
            [
                'kode_pelanggan' => 'PEG-0001',
                'nama_pelanggan' => 'Baiq Laila',
                'alamat_pelanggan' => 'Eceran Langsung',
                'no_hp' => '081234567890',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_pelanggan' => 'PEL-0002',
                'nama_pelanggan' => 'Nanda Salma',
                'alamat_pelanggan' => 'Kota Mataram',
                'no_hp' => '081987654321',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_pelanggan' => 'PEL-0003',
                'nama_pelanggan' => 'Siti Aminah',
                'alamat_pelanggan' => 'Senggigi',
                'no_hp' => '087765432109',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('pelanggan')->insert($pelanggans);
    }
}
