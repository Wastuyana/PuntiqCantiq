<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BatchProduksiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Matikan foreign key check & bersihkan tabel agar tidak terjadi duplikasi/error relasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('batch_hasil')->truncate();
        DB::table('batch')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. DATA UNTUK TABEL: batch
        $batches = [
            [
                'user_id' => 1,
                'id' => 1,
                'nomor_batch' => 'B-20260519-001',
                'tanggal_produksi' => '2026-05-19',
                'tanggal_kadaluarsa' => '2026-11-19',
                'status' => 'Selesai',
                'checklist_sop' => 1,
                'sop_details' => json_encode(['Peralatan Bersih', 'Bahan Baku Siap', 'Suhu Sesuai']),
                'biaya_bahan' => 1907200.00,
                'biaya_tenagakerja' => 725000.00,
                'biaya_overhead' => 190720.00,
                'created_at' => '2026-05-19 03:16:26',
                'updated_at' => '2026-05-19 03:16:26'
            ],
            [
                'user_id' => 1,
                'id' => 2,
                'nomor_batch' => 'B-20260519-002',
                'tanggal_produksi' => '2026-05-07',
                'tanggal_kadaluarsa' => '2026-11-19',
                'status' => 'Selesai',
                'checklist_sop' => 1,
                'sop_details' => json_encode(['Peralatan Bersih', 'Bahan Baku Siap', 'Suhu Sesuai']),
                'biaya_bahan' => 5468200.00,
                'biaya_tenagakerja' => 1406200.00,
                'biaya_overhead' => 546820.00,
                'created_at' => '2026-05-19 03:16:26',
                'updated_at' => '2026-05-19 03:16:26'
            ],
            [
                'user_id' => 1,
                'id' => 3,
                'nomor_batch' => 'B-20260519-003',
                'tanggal_produksi' => '2026-04-09',
                'tanggal_kadaluarsa' => '2026-11-19',
                'status' => 'Selesai',
                'checklist_sop' => 1,
                'sop_details' => json_encode(['Peralatan Bersih', 'Bahan Baku Siap', 'Suhu Sesuai']),
                'biaya_bahan' => 2662000.00,
                'biaya_tenagakerja' => 981100.00,
                'biaya_overhead' => 266200.00,
                'created_at' => '2026-05-19 03:16:26',
                'updated_at' => '2026-05-19 03:16:26'
            ],
            [
                'user_id' => 1,
                'id' => 4,
                'nomor_batch' => 'B-20260519-004',
                'tanggal_produksi' => '2026-04-23',
                'tanggal_kadaluarsa' => '2026-11-19',
                'status' => 'Selesai',
                'checklist_sop' => 1,
                'sop_details' => json_encode(['Peralatan Bersih', 'Bahan Baku Siap', 'Suhu Sesuai']),
                'biaya_bahan' => 6936840.00,
                'biaya_tenagakerja' => 2080000.00,
                'biaya_overhead' => 693684.00,
                'created_at' => '2026-05-19 03:16:26',
                'updated_at' => '2026-05-19 03:16:26'
            ],
            [
                'user_id' => 1,
                'id' => 5,
                'nomor_batch' => 'B-20260519-005',
                'tanggal_produksi' => '2026-04-15',
                'tanggal_kadaluarsa' => '2026-11-19',
                'status' => 'Selesai',
                'checklist_sop' => 1,
                'sop_details' => json_encode(['Peralatan Bersih', 'Bahan Baku Siap', 'Suhu Sesuai']),
                'biaya_bahan' => 4987000.00,
                'biaya_tenagakerja' => 1360400.00,
                'biaya_overhead' => 498700.00,
                'created_at' => '2026-05-19 03:16:26',
                'updated_at' => '2026-05-19 03:16:26'
            ],
            [
                'user_id' => 1,
                'id' => 6,
                'nomor_batch' => 'B-20260519-006',
                'tanggal_produksi' => '2026-04-10',
                'tanggal_kadaluarsa' => '2026-11-19',
                'status' => 'Selesai',
                'checklist_sop' => 1,
                'sop_details' => json_encode(['Peralatan Bersih', 'Bahan Baku Siap', 'Suhu Sesuai']),
                'biaya_bahan' => 7342860.00,
                'biaya_tenagakerja' => 1833000.00,
                'biaya_overhead' => 734286.00,
                'created_at' => '2026-05-19 03:16:26',
                'updated_at' => '2026-05-19 03:16:26'
            ]
        ];
        DB::table('batch')->insert($batches);

        // 3. DATA UNTUK TABEL: batch_hasil (Relasi Child Terikat batch_id)
        $batchHasil = [
            // Hasil Produksi Batch ID: 1
            [
                'id' => 1, 'batch_id' => 1, 'produk_id' => 1, 'hasil_target' => 90, 'hasil_aktual' => 90,
                'detail_biaya_bahan' => 9278.34, 'detail_biaya_tenagakerja' => 3500.00, 'detail_biaya_overhead' => 927.83, 'hpp_aktual' => 13706.18,
                'created_at' => '2026-05-19 03:20:00', 'updated_at' => '2026-05-19 03:20:00'
            ],
            [
                'id' => 2, 'batch_id' => 1, 'produk_id' => 2, 'hasil_target' => 100, 'hasil_aktual' => 100,
                'detail_biaya_bahan' => 6701.64, 'detail_biaya_tenagakerja' => 2000.00, 'detail_biaya_overhead' => 670.16, 'hpp_aktual' => 9371.80,
                'created_at' => '2026-05-19 03:20:00', 'updated_at' => '2026-05-19 03:20:00'
            ],
            [
                'id' => 3, 'batch_id' => 1, 'produk_id' => 4, 'hasil_target' => 70, 'hasil_aktual' => 70,
                'detail_biaya_bahan' => 5742.64, 'detail_biaya_tenagakerja' => 3000.00, 'detail_biaya_overhead' => 574.26, 'hpp_aktual' => 9316.91,
                'created_at' => '2026-05-19 03:20:00', 'updated_at' => '2026-05-19 03:20:00'
            ],

            // Hasil Produksi Batch ID: 2
            [
                'id' => 4, 'batch_id' => 2, 'produk_id' => 3, 'hasil_target' => 90, 'hasil_aktual' => 88,
                'detail_biaya_bahan' => 27155.36, 'detail_biaya_tenagakerja' => 4000.00, 'detail_biaya_overhead' => 2715.54, 'hpp_aktual' => 33870.89,
                'created_at' => '2026-05-19 03:25:00', 'updated_at' => '2026-05-19 03:25:00'
            ],
            [
                'id' => 5, 'batch_id' => 2, 'produk_id' => 5, 'hasil_target' => 100, 'hasil_aktual' => 98,
                'detail_biaya_bahan' => 11869.92, 'detail_biaya_tenagakerja' => 3400.00, 'detail_biaya_overhead' => 1186.99, 'hpp_aktual' => 16456.91,
                'created_at' => '2026-05-19 03:25:00', 'updated_at' => '2026-05-19 03:25:00'
            ],
            [
                'id' => 6, 'batch_id' => 2, 'produk_id' => 6, 'hasil_target' => 80, 'hasil_aktual' => 80,
                'detail_biaya_bahan' => 5777.54, 'detail_biaya_tenagakerja' => 3500.00, 'detail_biaya_overhead' => 577.75, 'hpp_aktual' => 9855.29,
                'created_at' => '2026-05-19 03:25:00', 'updated_at' => '2026-05-19 03:25:00'
            ],
            [
                'id' => 7, 'batch_id' => 2, 'produk_id' => 7, 'hasil_target' => 90, 'hasil_aktual' => 90,
                'detail_biaya_bahan' => 16145.26, 'detail_biaya_tenagakerja' => 4900.00, 'detail_biaya_overhead' => 1614.53, 'hpp_aktual' => 22659.79,
                'created_at' => '2026-05-19 03:25:00', 'updated_at' => '2026-05-19 03:25:00'
            ],

            // Hasil Produksi Batch ID: 3
            [
                'id' => 8, 'batch_id' => 3, 'produk_id' => 5, 'hasil_target' => 100, 'hasil_aktual' => 94,
                'detail_biaya_bahan' => 12401.30, 'detail_biaya_tenagakerja' => 3400.00, 'detail_biaya_overhead' => 1240.13, 'hpp_aktual' => 17041.43,
                'created_at' => '2026-05-19 03:30:00', 'updated_at' => '2026-05-19 03:30:00'
            ],
            [
                'id' => 9, 'batch_id' => 3, 'produk_id' => 6, 'hasil_target' => 100, 'hasil_aktual' => 93,
                'detail_biaya_bahan' => 6033.02, 'detail_biaya_tenagakerja' => 3500.00, 'detail_biaya_overhead' => 603.30, 'hpp_aktual' => 10136.33,
                'created_at' => '2026-05-19 03:30:00', 'updated_at' => '2026-05-19 03:30:00'
            ],
            [
                'id' => 10, 'batch_id' => 3, 'produk_id' => 9, 'hasil_target' => 100, 'hasil_aktual' => 96,
                'detail_biaya_bahan' => 9741.73, 'detail_biaya_tenagakerja' => 3500.00, 'detail_biaya_overhead' => 974.17, 'hpp_aktual' => 14215.90,
                'created_at' => '2026-05-19 03:30:00', 'updated_at' => '2026-05-19 03:30:00'
            ]
        ];
        DB::table('batch_hasil')->insert($batchHasil);
    }
}