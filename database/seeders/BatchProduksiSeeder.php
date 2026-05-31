<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BatchProduksiSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('batch')->truncate();
        DB::table('batch_bahan')->truncate();
        DB::table('batch_hasil')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $produkMap = DB::table('produk')->pluck('id', 'kode_produk')->toArray();
        $userId = DB::table('users')->first()->id ?? 1;
        $hargaBahanMap = DB::table('bahan_baku')->pluck('harga_satuan', 'id')->toArray();

        // Mengelompokkan seluruh 21 varian ke dalam 3 kluster produksi agar sebaran logis 
        // Kluster 1: Produk Reguler Utama & Mini Pack
        $klusterA = ['PCR-OR', 'PCR-CK', 'PCR-MB', 'PCR-GR', 'PCR-TL', 'PCM-CK', 'PCM-MB'];
        // Kluster 2: Big Pack (Kemasan Oleh-Oleh Besar)
        $klusterB = ['BP-OR500', 'BP-CK500', 'BP-MB500', 'BP-GR500', 'BP-TL500', 'BP-OR250', 'BP-CK250'];
        // Kluster 3: Sisa Big Pack, Toples Flakes, dan Crispy Jar
        $klusterC = ['BP-MB250', 'BP-GR250', 'BP-TL250', 'CBF-TPL', 'CBF-BIG', 'BCJ-CK', 'BCJ-TR'];

        // Gabungan semua produk untuk sesi produksi massal (misal saat persiapan lebaran)
        $semuaProduk = array_keys($produkMap);

        $jadwalProduksi = [
            // --- BULAN FEBRUARI (Aktivitas Normal) ---
            ['tanggal' => '2026-02-02', 'nomor' => 'B-20260202-01', 'tk' => 450000, 'varian' => $klusterA, 'qty' => 350],
            ['tanggal' => '2026-02-06', 'nomor' => 'B-20260206-01', 'tk' => 400000, 'varian' => $klusterB, 'qty' => 280],
            ['tanggal' => '2026-02-09', 'nomor' => 'B-20260209-01', 'tk' => 420000, 'varian' => $klusterC, 'qty' => 250],
            ['tanggal' => '2026-02-13', 'nomor' => 'B-20260213-01', 'tk' => 450000, 'varian' => $klusterA, 'qty' => 320],
            ['tanggal' => '2026-02-17', 'nomor' => 'B-20260217-01', 'tk' => 390000, 'varian' => $klusterB, 'qty' => 240],
            ['tanggal' => '2026-02-20', 'nomor' => 'B-20260220-01', 'tk' => 410000, 'varian' => $klusterC, 'qty' => 260],
            ['tanggal' => '2026-02-24', 'nomor' => 'B-20260224-01', 'tk' => 500000, 'varian' => $klusterA, 'qty' => 360],

            // --- BULAN MARET (Persiapan Ramadhan/Lebaran - Qty Dinaikkan Drastis) ---
            ['tanggal' => '2026-03-02', 'nomor' => 'B-20260302-01', 'tk' => 850000, 'varian' => $semuaProduk, 'qty' => 650],
            ['tanggal' => '2026-03-05', 'nomor' => 'B-20260305-01', 'tk' => 550000, 'varian' => $klusterB, 'qty' => 400],
            ['tanggal' => '2026-03-09', 'nomor' => 'B-20260309-01', 'tk' => 580000, 'varian' => $klusterC, 'qty' => 380],
            ['tanggal' => '2026-03-12', 'nomor' => 'B-20260312-01', 'tk' => 900000, 'varian' => $semuaProduk, 'qty' => 700],
            ['tanggal' => '2026-03-16', 'nomor' => 'B-20260316-01', 'tk' => 600000, 'varian' => $klusterA, 'qty' => 450],
            ['tanggal' => '2026-03-19', 'nomor' => 'B-20260319-01', 'tk' => 550000, 'varian' => $klusterB, 'qty' => 420],
            ['tanggal' => '2026-03-23', 'nomor' => 'B-20260323-01', 'tk' => 880000, 'varian' => $semuaProduk, 'qty' => 680],
            ['tanggal' => '2026-03-27', 'nomor' => 'B-20260327-01', 'tk' => 620000, 'varian' => $klusterC, 'qty' => 430],

            // --- BULAN APRIL (Pasca Lebaran / Stabil Berkelanjutan) ---
            ['tanggal' => '2026-04-02', 'nomor' => 'B-20260402-01', 'tk' => 450000, 'varian' => $klusterA, 'qty' => 340],
            ['tanggal' => '2026-04-06', 'nomor' => 'B-20260406-01', 'tk' => 400000, 'varian' => $klusterB, 'qty' => 290],
            ['tanggal' => '2026-04-09', 'nomor' => 'B-20260409-01', 'tk' => 420000, 'varian' => $klusterC, 'qty' => 280],
            ['tanggal' => '2026-04-13', 'nomor' => 'B-20260413-01', 'tk' => 450000, 'varian' => $klusterA, 'qty' => 310],
            ['tanggal' => '2026-04-16', 'nomor' => 'B-20260416-01', 'tk' => 410000, 'varian' => $klusterB, 'qty' => 270],
            ['tanggal' => '2026-04-20', 'nomor' => 'B-20260420-01', 'tk' => 430000, 'varian' => $klusterC, 'qty' => 290],
            ['tanggal' => '2026-04-24', 'nomor' => 'B-20260424-01', 'tk' => 800000, 'varian' => $semuaProduk, 'qty' => 600],
            ['tanggal' => '2026-04-28', 'nomor' => 'B-20260428-01', 'tk' => 400000, 'varian' => $klusterA, 'qty' => 300],

            // --- BULAN MEI (Bulan Berjalan - Stok Siap Masuk Menu Inventori) ---
            ['tanggal' => '2026-05-06', 'nomor' => 'B-20260506-01', 'tk' => 520000, 'varian' => $klusterA, 'qty' => 380],
            ['tanggal' => '2026-05-13', 'nomor' => 'B-20260513-01', 'tk' => 480000, 'varian' => $klusterB, 'qty' => 310],
            ['tanggal' => '2026-05-16', 'nomor' => 'B-20260516-01', 'tk' => 490000, 'varian' => $klusterC, 'qty' => 300],
            ['tanggal' => '2026-05-20', 'nomor' => 'B-20260520-01', 'tk' => 550000, 'varian' => $klusterA, 'qty' => 400],
            ['tanggal' => '2026-05-24', 'nomor' => 'B-20260524-01', 'tk' => 950000, 'varian' => $semuaProduk, 'qty' => 750], 
            ['tanggal' => '2026-05-28', 'nomor' => 'B-20260528-01', 'tk' => 500000, 'varian' => $klusterB, 'qty' => 250],
            ['tanggal' => '2026-05-28', 'nomor' => 'B-20260528-02', 'tk' => 500000, 'varian' => $klusterA, 'qty' => 150],
        ];

        foreach ($jadwalProduksi as $j) {
            $overhead = round($j['tk'] * 0.10);
            $jumlahVarianInBatch = count($j['varian']);
            $qtyPerVarianTarget = round($j['qty'] / $jumlahVarianInBatch);
            if ($qtyPerVarianTarget < 1) $qtyPerVarianTarget = 1;

            $batchId = DB::table('batch')->insertGetId([
                'nomor_batch'        => $j['nomor'],
                'tanggal_produksi'   => $j['tanggal'],
                'tanggal_kadaluarsa' => Carbon::parse($j['tanggal'])->addMonths(6)->format('Y-m-d'),
                'status'             => 'selesai',
                'checklist_sop'      => 1,
                'sop_details'        => 'SOP Manufaktur Terpenuhi dengan Penyesuaian Aktual',
                'biaya_bahan'        => 0,
                'biaya_tenagakerja'  => $j['tk'],
                'biaya_overhead'     => $overhead,
                'total_biaya'        => 0,
                'user_id'            => $userId,
                'created_at'         => Carbon::parse($j['tanggal']),
                'updated_at'         => Carbon::parse($j['tanggal']),
            ]);

            $totalBiayaBahanBatch = 0;
            $kebutuhanBahanBatch = [];

            foreach ($j['varian'] as $kodeProduk) {
                $idProd = $produkMap[$kodeProduk] ?? null;
                if (!$idProd) continue;

                $persentaseHasilAktual = rand(96, 102) / 100;
                $qtyPerVarianAktual = round($qtyPerVarianTarget * $persentaseHasilAktual);
                if ($qtyPerVarianAktual < 1) $qtyPerVarianAktual = 1;

                $bomItems = DB::table('bom')->where('produk_id', $idProd)->get();
                $totalBiayaBahanVarian = 0;

                foreach ($bomItems as $item) {
                    $qtyBahanTarget = $item->jumlah_kebutuhan * $qtyPerVarianTarget;
                    $persentaseBahanAktual = rand(97, 104) / 100;
                    $qtyBahanAktual = round($qtyBahanTarget * $persentaseBahanAktual);

                    $hargaBahanSatuan = $hargaBahanMap[$item->bahan_baku_id] ?? 0;
                    $qtyBahanAktualDalamKg = $qtyBahanAktual / 1000;
                    $totalBiayaBahanVarian += ($qtyBahanAktualDalamKg * $hargaBahanSatuan);

                    if (!isset($kebutuhanBahanBatch[$item->bahan_baku_id])) {
                        $kebutuhanBahanBatch[$item->bahan_baku_id] = [
                            'target' => 0,
                            'aktual' => 0
                        ];
                    }
                    $kebutuhanBahanBatch[$item->bahan_baku_id]['target'] += $qtyBahanTarget;
                    $kebutuhanBahanBatch[$item->bahan_baku_id]['aktual'] += $qtyBahanAktual;
                }

                $totalBiayaBahanBatch += $totalBiayaBahanVarian;

                $porsiTK = $j['tk'] / $jumlahVarianInBatch;
                $porsiOH = $overhead / $jumlahVarianInBatch;

                $hppAktual = $qtyPerVarianAktual > 0
                    ? (($totalBiayaBahanVarian + $porsiTK + $porsiOH) / $qtyPerVarianAktual)
                    : 0;

                DB::table('batch_hasil')->insert([
                    'batch_id'                 => $batchId,
                    'produk_id'                => $idProd,
                    'hasil_target'             => $qtyPerVarianTarget,
                    'hasil_aktual'             => $qtyPerVarianAktual,
                    'detail_biaya_bahan'       => round($totalBiayaBahanVarian, 2),
                    'detail_biaya_tenagakerja' => round($porsiTK, 2),
                    'detail_biaya_overhead'    => round($porsiOH, 2),
                    'hpp_aktual'               => round($hppAktual, 2),
                    'created_at'               => Carbon::parse($j['tanggal']),
                    'updated_at'               => Carbon::parse($j['tanggal']),
                ]);

                $bulanProduksi = Carbon::parse($j['tanggal'])->month;
                if ($bulanProduksi === 5) {
                    DB::table('produk')->where('id', $idProd)->increment('stok', $qtyPerVarianAktual);
                }
            }

            foreach ($kebutuhanBahanBatch as $bahanBakuId => $dataBahan) {
                DB::table('batch_bahan')->insert([
                    'batch_id'      => $batchId,
                    'bahan_baku_id' => $bahanBakuId,
                    'bahan_target'  => $dataBahan['target'],
                    'bahan_aktual'  => $dataBahan['aktual'],
                    'created_at'    => Carbon::parse($j['tanggal']),
                    'updated_at'    => Carbon::parse($j['tanggal']),
                ]);

                $penguranganStokKg = $dataBahan['aktual'] / 1000;
                DB::table('bahan_baku')->where('id', $bahanBakuId)->decrement('stok', $penguranganStokKg);
            }

            $totalBiayaSemua = $totalBiayaBahanBatch + $j['tk'] + $overhead;
            DB::table('batch')->where('id', $batchId)->update([
                'biaya_bahan' => round($totalBiayaBahanBatch, 2),
                'total_biaya' => round($totalBiayaSemua, 2)
            ]);
        }
    }
}
