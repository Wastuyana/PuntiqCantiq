<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BahanMasukSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bahan_masuk')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $bahanData = [
            // --- BAHAN UTAMA & UTILITY ---
            ['nama' => 'Pisang', 'lead_time' => 1, 'min_order' => 100, 'max_order' => 500],
            ['nama' => 'Minyak Goreng', 'lead_time' => 5, 'min_order' => 50, 'max_order' => 100],
            ['nama' => 'Gula', 'lead_time' => 4, 'min_order' => 1, 'max_order' => 30],
            ['nama' => 'Garam', 'lead_time' => 5, 'min_order' => 5, 'max_order' => 50],

            // --- BUMBU PERASA ---
            ['nama' => 'Bumbu Coklat M', 'lead_time' => 7, 'min_order' => 10, 'max_order' => 50],
            ['nama' => 'Bumbu Coklat G', 'lead_time' => 7, 'min_order' => 20, 'max_order' => 50],
            ['nama' => 'Bumbu Mentega', 'lead_time' => 7, 'min_order' => 20, 'max_order' => 50],
            ['nama' => 'Bumbu Susu', 'lead_time' => 7, 'min_order' => 20, 'max_order' => 50],
            ['nama' => 'Bumbu Garlic', 'lead_time' => 7, 'min_order' => 20, 'max_order' => 50],
            ['nama' => 'Bumbu Garlic non msg', 'lead_time' => 7, 'min_order' => 20, 'max_order' => 50],
            ['nama' => 'Bumbu Taliwang', 'lead_time' => 7, 'min_order' => 20, 'max_order' => 50],

            // Kemasan PCR Series
            ['nama' => 'Kemasan PCR Coklat', 'lead_time' => 14, 'min_order' => 200, 'max_order' => 1000],
            ['nama' => 'Kemasan PCR Milky', 'lead_time' => 14, 'min_order' => 200, 'max_order' => 1000],
            ['nama' => 'Kemasan PCR Garlic', 'lead_time' => 14, 'min_order' => 200, 'max_order' => 1000],
            ['nama' => 'Kemasan PCR Taliwang', 'lead_time' => 14, 'min_order' => 200, 'max_order' => 1000],
            ['nama' => 'Kemasan PCR Original', 'lead_time' => 14, 'min_order' => 200, 'max_order' => 1000],

            // Kemasan PCM Series
            ['nama' => 'Kemasan pcm Coklat', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],
            ['nama' => 'Kemasan pcm Milky', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],

            // Utility Packaging
            ['nama' => 'Toples CBF', 'lead_time' => 7, 'min_order' => 100, 'max_order' => 1000],

            // Kemasan BP 500g Series
            ['nama' => 'Kemasan BP 500g Original', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],
            ['nama' => 'Kemasan BP 500g Coklat', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],
            ['nama' => 'Kemasan BP 500g Milky', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],
            ['nama' => 'Kemasan BP 500g Garlic', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],
            ['nama' => 'Kemasan BP 500g Taliwang', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],

            // Kemasan BP 250g Series (Perhatikan spasi ganda bawaan dari seeder kamu)
            ['nama' => 'Kemasan BP 250g Original', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],
            ['nama' => 'Kemasan BP 250g  Coklat', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000], // Spasi ganda dipertahankan sesuai master
            ['nama' => 'Kemasan BP 250g Milky', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],
            ['nama' => 'Kemasan BP 250g Garlic', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],
            ['nama' => 'Kemasan BP 250g Taliwang', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],

            // Kemasan BJC Series
            ['nama' => 'Kemasan BJC Coklat', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],
            ['nama' => 'Kemasan BJC Tiramisu', 'lead_time' => 14, 'min_order' => 100, 'max_order' => 1000],
        ];

        foreach ($bahanData as $data) {
            $bahan = DB::table('bahan_baku')->where('nama', $data['nama'])->first();
            $supplier = DB::table('supplier')->inRandomOrder()->first();

            if ($bahan && $supplier) {
                for ($i = 0; $i < 3; $i++) {

                    $day = rand(1, 28); 
                    $tanggalPesan = Carbon::create(2026, 5, $day); 
                    $jumlah = rand($data['min_order'], $data['max_order']);

                    $bulanPesanan = Carbon::parse($tanggalPesan)->month;

                    if ($bulanPesanan === 5) {

                        DB::table('bahan_masuk')->insert([
                            'kode_pesanan'     => 'PO-' . $tanggalPesan->format('ymd') . '-' . rand(100, 999),
                            'jumlah_pesan'     => $jumlah,
                            'proses_pemesanan' => 'selesai_dicatat',
                            'supplier_id'      => $supplier->id,
                            'bahan_baku_id'    => $bahan->id,
                            'tanggal_masuk'    => $tanggalPesan->copy()->addDays($data['lead_time'])->format('Y-m-d'),
                            'tanggal_pesan'    => $tanggalPesan->format('Y-m-d'),
                            'jumlah_total'     => $jumlah,
                            'harga_beli'       => ($bahan->harga_satuan ?? 1000) * 0.95,
                            'status'           => 'completed',
                            'created_at'       => $tanggalPesan,
                            'updated_at'       => $tanggalPesan,
                        ]);

                        DB::table('bahan_baku')->where('id', $bahan->id)->increment('stok', $jumlah);
                    }
                }
            }
        }
    }
}