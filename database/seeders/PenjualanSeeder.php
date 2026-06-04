<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('penjualan')->truncate();
        DB::table('detail_penjualan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $produkMap = DB::table('produk')->pluck('id', 'kode_produk')->toArray();
        $mitraIds = DB::table('mitra')->pluck('id')->toArray();
        $pelangganIds = DB::table('pelanggan')->pluck('id')->toArray();

        // Helper function untuk generate kode
        $generateKode = function ($tanggal) {
            $formattedDate = Carbon::parse($tanggal)->format('Ymd');
            $lastPenjualan = DB::table('penjualan')
                ->whereDate('tanggal_penj', Carbon::parse($tanggal)->format('Y-m-d'))
                ->latest('id')
                ->first();

            $nextNumber = $lastPenjualan && preg_match('/-(\d{3})$/', $lastPenjualan->kode_penjualan, $matches) 
                ? (intval($matches[1]) + 1) 
                : 1;

            return 'INV-' . $formattedDate . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        };

        $rekapBulananMitra = [
            2 => [
                'total_target' => 700,
                'tanggal_pilihan' => ['2026-02-05', '2026-02-12', '2026-02-19', '2026-02-26'],
                'produk_distribusi' => [
                    'PCR-OR' => 0.15,
                    'PCR-CK' => 0.20,
                    'PCR-MB' => 0.15,
                    'PCR-GR' => 0.10,
                    'PCR-TL' => 0.05,
                    'PCM-CK' => 0.05,
                    'PCM-MB' => 0.05,
                    'CBF-TPL' => 0.03,
                    'CBF-BIG' => 0.02,
                    'BP-OR500' => 0.02,
                    'BP-CK500' => 0.02,
                    'BP-MB500' => 0.02,
                    'BP-GR500' => 0.02,
                    'BP-TL500' => 0.02,
                    'BP-OR250' => 0.03,
                    'BP-CK250' => 0.03,
                    'BP-MB250' => 0.03,
                    'BP-GR250' => 0.02,
                    'BP-TL250' => 0.02,
                    'BCJ-CK' => 0.03,
                    'BCJ-TR' => 0.02
                ]
            ],
            3 => [
                'total_target' => 980, 
                'tanggal_pilihan' => ['2026-03-04', '2026-03-11', '2026-03-18', '2026-03-25', '2026-03-30'],
                'produk_distribusi' => [
                    'PCR-OR' => 0.12,
                    'PCR-CK' => 0.18,
                    'PCR-MB' => 0.15,
                    'PCR-GR' => 0.08,
                    'PCR-TL' => 0.05,
                    'PCM-CK' => 0.05,
                    'PCM-MB' => 0.05,
                    'CBF-TPL' => 0.05,
                    'CBF-BIG' => 0.03,
                    'BP-OR500' => 0.04,
                    'BP-CK500' => 0.04,
                    'BP-MB500' => 0.04,
                    'BP-GR500' => 0.02,
                    'BP-TL500' => 0.02,
                    'BP-OR250' => 0.03,
                    'BP-CK250' => 0.03,
                    'BP-MB250' => 0.03,
                    'BP-GR250' => 0.02,
                    'BP-TL250' => 0.02,
                    'BCJ-CK' => 0.04,
                    'BCJ-TR' => 0.03
                ]
            ],
            4 => [
                'total_target' => 951,
                'tanggal_pilihan' => ['2026-04-03', '2026-04-10', '2026-04-17', '2026-04-24'],
                'produk_distribusi' => [
                    'PCR-OR' => 0.14,
                    'PCR-CK' => 0.20,
                    'PCR-MB' => 0.14,
                    'PCR-GR' => 0.10,
                    'PCR-TL' => 0.05,
                    'PCM-CK' => 0.05,
                    'PCM-MB' => 0.05,
                    'CBF-TPL' => 0.03,
                    'CBF-BIG' => 0.02,
                    'BP-OR500' => 0.02,
                    'BP-CK500' => 0.02,
                    'BP-MB500' => 0.02,
                    'BP-GR500' => 0.02,
                    'BP-TL500' => 0.02,
                    'BP-OR250' => 0.03,
                    'BP-CK250' => 0.03,
                    'BP-MB250' => 0.03,
                    'BP-GR250' => 0.02,
                    'BP-TL250' => 0.02,
                    'BCJ-CK' => 0.03,
                    'BCJ-TR' => 0.02
                ]
            ],
            5 => [
                'total_target' => 950,
                'tanggal_pilihan' => ['2026-05-04', '2026-05-11', '2026-05-18', '2026-05-25'],
                'produk_distribusi' => [
                    'PCR-OR' => 0.15,
                    'PCR-CK' => 0.18,
                    'PCR-MB' => 0.15,
                    'PCR-GR' => 0.09,
                    'PCR-TL' => 0.05,
                    'PCM-CK' => 0.05,
                    'PCM-MB' => 0.05,
                    'CBF-TPL' => 0.04,
                    'CBF-BIG' => 0.02,
                    'BP-OR500' => 0.02,
                    'BP-CK500' => 0.02,
                    'BP-MB500' => 0.02,
                    'BP-GR500' => 0.02,
                    'BP-TL500' => 0.02,
                    'BP-OR250' => 0.03,
                    'BP-CK250' => 0.03,
                    'BP-MB250' => 0.03,
                    'BP-GR250' => 0.02,
                    'BP-TL250' => 0.02,
                    'BCJ-CK' => 0.03,
                    'BCJ-TR' => 0.02
                ]
            ],

            6 => [
                'total_target' => 90,
                'tanggal_pilihan' => ['2026-05-04', '2026-05-6'],
                'produk_distribusi' => [
                    'PCR-OR' => 0.15,
                    'PCR-CK' => 0.18,
                    'PCR-MB' => 0.15,
                    'PCR-GR' => 0.09,
                    'PCR-TL' => 0.05,
                    'PCM-CK' => 0.05,
                    'PCM-MB' => 0.05,
                    'CBF-TPL' => 0.04,
                    'BCJ-CK' => 0.03,
                    'BCJ-TR' => 0.02
                ]
            ]
        ];

        foreach ($rekapBulananMitra as $bulan => $config) {
            $jumlahTanggal = count($config['tanggal_pilihan']);
            $targetPerTransaksi = floor($config['total_target'] / $jumlahTanggal);
            $sisaBungkus = $config['total_target'] % $jumlahTanggal;

            foreach ($config['tanggal_pilihan'] as $index => $tanggal) {
                $qtyTransaksiIni = $targetPerTransaksi + ($index === 0 ? $sisaBungkus : 0);
                $mitraIdTerpilih = $mitraIds[array_rand($mitraIds)];

                $penjualanId = DB::table('penjualan')->insertGetId([
                    'kode_penjualan'    => $generateKode($tanggal),
                    'tanggal_penj'      => $tanggal,
                    'total_prod'        => 0,
                    'subtotal_harga'    => 0,
                    'status_customer'   => 'mitra',
                    'metode_pembayaran' => 'transfer',
                    'mitra_id'          => $mitraIds[array_rand($mitraIds)],
                    'created_at'        => Carbon::parse($tanggal),
                    'updated_at'        => Carbon::parse($tanggal),
                ]);

                $totalProdTransaksi = 0;
                $subtotalHargaTransaksi = 0;

                foreach ($config['produk_distribusi'] as $kodeProduk => $persentase) {
                    $faktorAcak = rand(65, 135) / 100;
                    $qtyProduk = round(($qtyTransaksiIni * $persentase) * $faktorAcak);

                    if ($qtyProduk > 0) {
                        $idProduk = $produkMap[$kodeProduk] ?? null;
                        if ($idProduk) {
                            $produk = DB::table('produk')->where('id', $idProduk)->first();
                            $totalHargaDetail = $qtyProduk * $produk->harga_jual;

                            DB::table('detail_penjualan')->insert([
                                'penjualan_id'  => $penjualanId,
                                'produk_id'     => $idProduk,
                                'jumlah_produk' => $qtyProduk,
                                'total_harga'   => $totalHargaDetail,
                                'created_at'    => Carbon::parse($tanggal),
                                'updated_at'    => Carbon::parse($tanggal),
                            ]);

                            if ($bulan === 5 && $bulan === 6) {
                                DB::table('produk')->where('id', $idProduk)->decrement('stok', $qtyProduk);
                            }

                            $totalProdTransaksi += $qtyProduk;
                            $subtotalHargaTransaksi += $totalHargaDetail;
                        }
                    }
                }

                DB::table('penjualan')->where('id', $penjualanId)->update([
                    'total_prod'     => $totalProdTransaksi,
                    'subtotal_harga' => $subtotalHargaTransaksi
                ]);
            }
        }

        $metodePembayaran = ['cash', 'qris', 'cash'];
        $produkKodes = array_keys($produkMap);

        for ($i = 1; $i <= 200; $i++) {
            $tanggalAcak = "2026-" . sprintf("%02d", rand(2, 5)) . "-" . sprintf("%02d", rand(1, 28));

            $penjualanId = DB::table('penjualan')->insertGetId([
                'kode_penjualan'    => $generateKode($tanggalAcak),
                'tanggal_penj'      => $tanggalAcak,
                'total_prod'        => 0,
                'subtotal_harga'    => 0,
                'status_customer'   => 'pelanggan',
                'pelanggan_id'      => !empty($pelangganIds) ? $pelangganIds[array_rand($pelangganIds)] : null,
                'metode_pembayaran' => $metodePembayaran[array_rand($metodePembayaran)],
                'mitra_id'          => null,
                'created_at'        => Carbon::parse($tanggalAcak),
                'updated_at'        => Carbon::parse($tanggalAcak),
            ]);

            $qtyBeliEceran = rand(1, 5);
            $kodeTerpilih = $produkKodes[array_rand($produkKodes)];
            $idProduk = $produkMap[$kodeTerpilih];

            $produk = DB::table('produk')->where('id', $idProduk)->first();
            $totalHargaDetail = $qtyBeliEceran * $produk->harga_jual;

            DB::table('detail_penjualan')->insert([
                'penjualan_id'  => $penjualanId,
                'produk_id'     => $idProduk,
                'jumlah_produk' => $qtyBeliEceran,
                'total_harga'   => $totalHargaDetail,
                'created_at'    => Carbon::parse($tanggalAcak),
                'updated_at'    => Carbon::parse($tanggalAcak),
            ]);

            DB::table('penjualan')->where('id', $penjualanId)->update([
                'total_prod'     => $qtyBeliEceran,
                'subtotal_harga' => $totalHargaDetail
            ]);
        }
    }
}
