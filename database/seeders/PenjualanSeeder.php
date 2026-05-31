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

        if (empty($mitraIds)) {
            $this->command->error("Tabel mitra masih kosong! Jalankan MitraSeeder terlebih dahulu.");
            return;
        }

        $rekapBulananMitra = [
            2 => [
                'total_target' => 473,
                'tanggal_pilihan' => ['2026-02-05', '2026-02-12', '2026-02-19', '2026-02-26'],
                'produk_distribusi' => [
                    'PCR-CK' => 0.50,
                    'PCR-MB' => 0.20,
                    'PCR-GR' => 0.15,
                    'PCM-CK' => 0.10,
                    'BCJ-CK' => 0.05
                ]
            ],
            3 => [
                'total_target' => 763,
                'tanggal_pilihan' => ['2026-03-04', '2026-03-11', '2026-03-18', '2026-03-25', '2026-03-30'],
                'produk_distribusi' => [
                    'PCR-CK' => 0.45,
                    'PCR-MB' => 0.25, // Varian manis naik pas lebaran
                    'PCR-TL' => 0.10, // Ayam Taliwang
                    'BP-OR500' => 0.10, // Big Pack buat parsel/oleh-oleh kue lebaran
                    'BCJ-TR' => 0.10   // Crispy Jar Tiramisu
                ]
            ],
            4 => [
                'total_target' => 689,
                'tanggal_pilihan' => ['2026-04-03', '2026-04-10', '2026-04-17', '2026-04-24'],
                'produk_distribusi' => [
                    'PCR-CK' => 0.48,
                    'PCR-MB' => 0.22,
                    'PCR-GR' => 0.15,
                    'PCM-MB' => 0.10,
                    'BP-CK250' => 0.05
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
                    'tanggal_penj'      => $tanggal,
                    'total_prod'        => 0,
                    'subtotal_harga'    => 0,
                    'status_customer'   => 'mitra',
                    'pelanggan_id'      => null,
                    'metode_pembayaran' => 'transfer',
                    'mitra_id'          => $mitraIdTerpilih,
                    'created_at'        => Carbon::parse($tanggal),
                    'updated_at'        => Carbon::parse($tanggal),
                ]);

                $totalProdTransaksi = 0;
                $subtotalHargaTransaksi = 0;

                foreach ($config['produk_distribusi'] as $kodeProduk => $persentase) {
                    $faktorAcak = rand(70, 130) / 100;
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

        for ($i = 1; $i <= 30; $i++) {
            $bulanAcak = rand(2, 4);
            $hariAcak = rand(1, 28);
            $tanggalAcak = "2026-0" . $bulanAcak . "-" . sprintf("%02d", $hariAcak);
            $pelangganId = !empty($pelangganIds) ? $pelangganIds[array_rand($pelangganIds)] : null;

            $penjualanId = DB::table('penjualan')->insertGetId([
                'tanggal_penj'      => $tanggalAcak,
                'total_prod'        => 0,
                'subtotal_harga'    => 0,
                'status_customer'   => 'pelanggan',
                'pelanggan_id'      => $pelangganId,
                'metode_pembayaran' => $metodePembayaran[array_rand($metodePembayaran)],
                'mitra_id'          => null,
                'created_at'        => Carbon::parse($tanggalAcak),
                'updated_at'        => Carbon::parse($tanggalAcak),
            ]);

            $qtyBeliEceran = rand(1, 3);
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
