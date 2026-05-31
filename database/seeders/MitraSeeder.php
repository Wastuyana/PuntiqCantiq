<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MitraSeeder extends Seeder
{
    public function run()
    {
        // Daftar nama toko/mitra unik dari Gambar 4 & 5 data penjualanmu
        $daftarToko = [
            'Ruby',
            'Beras Bulu',
            'kopte tarik',
            'M Mart',
            'Toko swadaya',
            'Malika Bakery',
            'NTB Mall',
            'ECM',
            'mulia jaje and bakery',
            'Maharani',
            'kedai maneas',
            'Niaga Cakra',
            'Niaga Sriwijaya',
            'Niaga Ampenan',
            'Sari rasa',
            'RGM rembige',
            'RGM pjtilar',
            'Hai Snack',
            'Papa cookies',
            'Amanda',
            'Toko Cakrawala',
            'Titanik Nusantara',
            'Goyang Lidah',
            'Rollpin',
            'Madam',
            'teras mayn',
            'Sasaku senggigi',
            'Sasaku dscrmn',
            'OMAH',
            'LESTARI',
            'WAHANA',
            'GANDRUNG',
            'Rumah sosis',
            'Geprek Berlian',
            'Pesona jajan',
            'LOMBOK EXOTIC'
        ];

        $i = 1;
        foreach ($daftarToko as $toko) {
            // Membuat format kode MTR-001, MTR-002, dst.
            $kodeMitra = 'MTR-' . str_pad($i, 3, '0', STR_PAD_LEFT);

            // Lokasi default (bisa disesuaikan nanti)
            $alamat = 'Lombok / Mataram';
            if (str_contains(strtolower($toko), 'senggigi')) $alamat = 'Senggigi';
            if (str_contains(strtolower($toko), 'rembige')) $alamat = 'Rembiga';
            if (str_contains(strtolower($toko), 'cakra')) $alamat = 'Cakranegara';

            DB::table('mitra')->insert([
                'kode_mitra' => $kodeMitra,
                'nama_mitra' => $toko,
                'alamat_mitra' => $alamat,
                'no_hp' => '0819' . rand(100000, 999999), // Angka random agar cepat terisi data dummy
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $i++;
        }
    }
}
