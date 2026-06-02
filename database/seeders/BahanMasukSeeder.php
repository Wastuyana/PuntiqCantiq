<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BahanMasukSeeder extends Seeder
{
    public function run()
    {
        $bahanData = [
            ['nama' => 'Pisang', 'lead_time' => 1],
            ['nama' => 'Minyak', 'lead_time' => 5],
            ['nama' => 'Gula', 'lead_time' => 4],
            ['nama' => 'Garam', 'lead_time' => 5],
            ['nama' => 'Perasa', 'lead_time' => 7],
            ['nama' => 'Keripik pisang', 'lead_time' => 3],
            ['nama' => 'Rice crispy', 'lead_time' => 7],
            ['nama' => 'Glaze', 'lead_time' => 7],
            ['nama' => 'Coklat', 'lead_time' => 7],
            ['nama' => 'Kemasan PCR Coklat', 'lead_time' => 14],
            ['nama' => 'Kemasan PCR Milky', 'lead_time' => 14],
            ['nama' => 'Kemasan PCR Garlic', 'lead_time' => 14],
            ['nama' => 'Kemasan PCR Taliwang', 'lead_time' => 14],
            ['nama' => 'Kemasan PCM Coklat', 'lead_time' => 14],
            ['nama' => 'Kemasan PCM Milky', 'lead_time' => 14],
        ];

        foreach ($bahanData as $data) {
            $bahan = DB::table('bahan_baku')->where('nama', $data['nama'])->first();
            $supplier = DB::table('supplier')->inRandomOrder()->first();

            if ($bahan && $supplier) {
                for ($i = 0; $i < 3; $i++) {
                    $tanggalPesan = Carbon::now()->subDays(rand(1, 30)); 
                    $jumlah = rand(50, 200);
                    
                    DB::table('bahan_masuk')->insert([
                        'kode_pesanan'     => 'PO-' . $tanggalPesan->format('ymd') . '-' . rand(100, 999),
                        'jumlah_pesan'     => $jumlah, 
                        'proses_pemesanan' => 'selesai_dicatat',
                        'supplier_id'      => $supplier->id,
                        'bahan_baku_id'    => $bahan->id,
                        'tanggal_masuk'    => $tanggalPesan->copy()->addDays($data['lead_time'])->format('Y-m-d'),
                        'tanggal_pesan'    => $tanggalPesan->format('Y-m-d'),
                        'jumlah_total'     => $jumlah, 
                        'harga_beli'       => rand(1000, 2500), 
                        'status'           => 'completed', 
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                }
            }
        }
    }
}