<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BomSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bom')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $boms = [
            // 1. PC Original 90g 
            ['produk_id' => 1, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.130],
            ['produk_id' => 1, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 40],   // Minyak (ml)
            ['produk_id' => 1, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 1.80], // Gula (gram)
            ['produk_id' => 1, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.15], // Garam (gram)
            ['produk_id' => 1, 'bahan_baku_id' => 18, 'jumlah_kebutuhan' => 1],   // Kemasan (pcs)

            // 2. PC Coklat 85g 
            ['produk_id' => 2, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.118],
            ['produk_id' => 2, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 36],
            ['produk_id' => 2, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 1.68],
            ['produk_id' => 2, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.14],
            ['produk_id' => 2, 'bahan_baku_id' => 5, 'jumlah_kebutuhan' => 10.40], // Bumbu Coklat M (gram)
            ['produk_id' => 2, 'bahan_baku_id' => 6, 'jumlah_kebutuhan' => 2.60],  // Bumbu Coklat G (gram)
            ['produk_id' => 2, 'bahan_baku_id' => 12, 'jumlah_kebutuhan' => 1],

            // 3. PC Milky Butter 85g
            ['produk_id' => 3, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.125],
            ['produk_id' => 3, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 38],
            ['produk_id' => 3, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 1.80],
            ['produk_id' => 3, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.15],
            ['produk_id' => 3, 'bahan_baku_id' => 7, 'jumlah_kebutuhan' => 4.25],  // Bumbu Mentega (gram)
            ['produk_id' => 3, 'bahan_baku_id' => 8, 'jumlah_kebutuhan' => 4.25],  // Bumbu Susu (gram)
            ['produk_id' => 3, 'bahan_baku_id' => 13, 'jumlah_kebutuhan' => 1],

            // 4. PC Garlic 90g
            ['produk_id' => 4, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.140],
            ['produk_id' => 4, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 43],
            ['produk_id' => 4, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 2.04],
            ['produk_id' => 4, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.17],
            ['produk_id' => 4, 'bahan_baku_id' => 9, 'jumlah_kebutuhan' => 2.70],  // Bumbu Garlic (gram)
            ['produk_id' => 4, 'bahan_baku_id' => 10, 'jumlah_kebutuhan' => 1.80], // Bumbu Garlic non msg
            ['produk_id' => 4, 'bahan_baku_id' => 14, 'jumlah_kebutuhan' => 1],

            // 5. PC Taliwang 65g
            ['produk_id' => 5, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.090],
            ['produk_id' => 5, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 28],
            ['produk_id' => 5, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 1.30],
            ['produk_id' => 5, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.10],
            ['produk_id' => 5, 'bahan_baku_id' => 11, 'jumlah_kebutuhan' => 4.50], // Bumbu Taliwang
            ['produk_id' => 5, 'bahan_baku_id' => 15, 'jumlah_kebutuhan' => 1],

            // 6. PC Coklat 40g
            ['produk_id' => 6, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.056],
            ['produk_id' => 6, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 17],
            ['produk_id' => 6, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 0.84],
            ['produk_id' => 6, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.07],
            ['produk_id' => 6, 'bahan_baku_id' => 5, 'jumlah_kebutuhan' => 4.80],
            ['produk_id' => 6, 'bahan_baku_id' => 6, 'jumlah_kebutuhan' => 1.20],
            ['produk_id' => 6, 'bahan_baku_id' => 16, 'jumlah_kebutuhan' => 1],

            // 7. PC Milky Butter 40g
            ['produk_id' => 7, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.059],
            ['produk_id' => 7, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 18],
            ['produk_id' => 7, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 0.84],
            ['produk_id' => 7, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.07],
            ['produk_id' => 7, 'bahan_baku_id' => 7, 'jumlah_kebutuhan' => 2.00],
            ['produk_id' => 7, 'bahan_baku_id' => 8, 'jumlah_kebutuhan' => 2.00],
            ['produk_id' => 7, 'bahan_baku_id' => 17, 'jumlah_kebutuhan' => 1],

            // 8. Choco Banana Flakes (180g)
            ['produk_id' => 8, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.240],
            ['produk_id' => 8, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 75],
            ['produk_id' => 8, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 3.50],
            ['produk_id' => 8, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.25],
            ['produk_id' => 8, 'bahan_baku_id' => 5, 'jumlah_kebutuhan' => 22.00],
            ['produk_id' => 8, 'bahan_baku_id' => 19, 'jumlah_kebutuhan' => 1],

            // 9. BP 500g Ori
            ['produk_id' => 9, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.720],
            ['produk_id' => 9, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 220],
            ['produk_id' => 9, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 10.00],
            ['produk_id' => 9, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.80],
            ['produk_id' => 9, 'bahan_baku_id' => 20, 'jumlah_kebutuhan' => 1],

            // 10. BP 500g Coklat
            ['produk_id' => 10, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.650],
            ['produk_id' => 10, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 200],
            ['produk_id' => 10, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 9.20],
            ['produk_id' => 10, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.75],
            ['produk_id' => 10, 'bahan_baku_id' => 5, 'jumlah_kebutuhan' => 57.00],
            ['produk_id' => 10, 'bahan_baku_id' => 6, 'jumlah_kebutuhan' => 14.30],
            ['produk_id' => 10, 'bahan_baku_id' => 21, 'jumlah_kebutuhan' => 1],

            // 11. BP 500g Milky
            ['produk_id' => 11, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.680],
            ['produk_id' => 11, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 210],
            ['produk_id' => 11, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 10.00],
            ['produk_id' => 11, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.80],
            ['produk_id' => 11, 'bahan_baku_id' => 7, 'jumlah_kebutuhan' => 23.40],
            ['produk_id' => 11, 'bahan_baku_id' => 8, 'jumlah_kebutuhan' => 23.40],
            ['produk_id' => 11, 'bahan_baku_id' => 22, 'jumlah_kebutuhan' => 1],

            // 12. BP 500g Garlic
            ['produk_id' => 12, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.770],
            ['produk_id' => 12, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 240],
            ['produk_id' => 12, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 11.20],
            ['produk_id' => 12, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.93],
            ['produk_id' => 12, 'bahan_baku_id' => 9, 'jumlah_kebutuhan' => 15.00],
            ['produk_id' => 12, 'bahan_baku_id' => 10, 'jumlah_kebutuhan' => 10.00],
            ['produk_id' => 12, 'bahan_baku_id' => 23, 'jumlah_kebutuhan' => 1],

            // 13. BP 500g Taliwang
            ['produk_id' => 13, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.690],
            ['produk_id' => 13, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 215],
            ['produk_id' => 13, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 10.00],
            ['produk_id' => 13, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.77],
            ['produk_id' => 13, 'bahan_baku_id' => 11, 'jumlah_kebutuhan' => 34.60],
            ['produk_id' => 13, 'bahan_baku_id' => 24, 'jumlah_kebutuhan' => 1],

            // 14. BP 250g Ori
            ['produk_id' => 14, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.360],
            ['produk_id' => 14, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 110],
            ['produk_id' => 14, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 5.00],
            ['produk_id' => 14, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.40],
            ['produk_id' => 14, 'bahan_baku_id' => 25, 'jumlah_kebutuhan' => 1],

            // 15. BP 250g Coklat
            ['produk_id' => 15, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.330],
            ['produk_id' => 15, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 100],
            ['produk_id' => 15, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 4.60],
            ['produk_id' => 15, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.38],
            ['produk_id' => 15, 'bahan_baku_id' => 5, 'jumlah_kebutuhan' => 29.00],
            ['produk_id' => 15, 'bahan_baku_id' => 6, 'jumlah_kebutuhan' => 7.20],
            ['produk_id' => 15, 'bahan_baku_id' => 26, 'jumlah_kebutuhan' => 1],

            // 16. BP 250g Milky
            ['produk_id' => 16, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.340],
            ['produk_id' => 16, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 105],
            ['produk_id' => 16, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 5.00],
            ['produk_id' => 16, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.40],
            ['produk_id' => 16, 'bahan_baku_id' => 7, 'jumlah_kebutuhan' => 11.90],
            ['produk_id' => 16, 'bahan_baku_id' => 8, 'jumlah_kebutuhan' => 11.90],
            ['produk_id' => 16, 'bahan_baku_id' => 27, 'jumlah_kebutuhan' => 1],

            // 17. BP 250g Garlic
            ['produk_id' => 17, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.390],
            ['produk_id' => 17, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 120],
            ['produk_id' => 17, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 5.60],
            ['produk_id' => 17, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.47],
            ['produk_id' => 17, 'bahan_baku_id' => 9, 'jumlah_kebutuhan' => 7.50],
            ['produk_id' => 17, 'bahan_baku_id' => 10, 'jumlah_kebutuhan' => 5.00],
            ['produk_id' => 17, 'bahan_baku_id' => 28, 'jumlah_kebutuhan' => 1],

            // 18. BP 250g Taliwang
            ['produk_id' => 18, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.345],
            ['produk_id' => 18, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 108],
            ['produk_id' => 18, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 5.00],
            ['produk_id' => 18, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.38],
            ['produk_id' => 18, 'bahan_baku_id' => 11, 'jumlah_kebutuhan' => 17.30],
            ['produk_id' => 18, 'bahan_baku_id' => 29, 'jumlah_kebutuhan' => 1],

            // 19. BCJ Coklat
            ['produk_id' => 19, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.150],
            ['produk_id' => 19, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 45],
            ['produk_id' => 19, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 2.00],
            ['produk_id' => 19, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.15],
            ['produk_id' => 19, 'bahan_baku_id' => 5, 'jumlah_kebutuhan' => 15.00],
            ['produk_id' => 19, 'bahan_baku_id' => 30, 'jumlah_kebutuhan' => 1],

            // 20. BCJ Tiramisu
            ['produk_id' => 20, 'bahan_baku_id' => 1, 'jumlah_kebutuhan' => 0.150],
            ['produk_id' => 20, 'bahan_baku_id' => 2, 'jumlah_kebutuhan' => 45],
            ['produk_id' => 20, 'bahan_baku_id' => 3, 'jumlah_kebutuhan' => 2.00],
            ['produk_id' => 20, 'bahan_baku_id' => 4, 'jumlah_kebutuhan' => 0.15],
            ['produk_id' => 20, 'bahan_baku_id' => 8, 'jumlah_kebutuhan' => 15.00],
            ['produk_id' => 20, 'bahan_baku_id' => 31, 'jumlah_kebutuhan' => 1],
        ];

        foreach ($boms as $bom) {
            $bom['created_at'] = now();
            $bom['updated_at'] = now();
            DB::table('bom')->insert($bom);
        }

        $produkIds = array_unique(array_column($boms, 'produk_id'));

        foreach ($produkIds as $produkId) {
            // Ambil semua item BoM milik produk saat ini beserta detail bahan bakunya
            $bomItems = DB::table('bom')
                ->join('bahan_baku', 'bom.bahan_baku_id', '=', 'bahan_baku.id')
                ->where('bom.produk_id', $produkId)
                ->select('bom.jumlah_kebutuhan', 'bahan_baku.satuan', 'bahan_baku.harga_satuan')
                ->get();

            $totalBahan = 0;

            // Loop tiap bahan baku untuk menghitung total biaya bahan (termasuk konversi)
            foreach ($bomItems as $item) {
                $jumlahBoM = $item->jumlah_kebutuhan;
                $satuanMaster = strtolower($item->satuan);
                $hargaMaster = $item->harga_satuan;

                // Logika konversi satuan persis seperti rumusmu
                if (in_array($satuanMaster, ['kg', 'liter', 'l'])) {
                    $jumlahKebutuhanKonversi = $jumlahBoM / 1000;
                } else {
                    $jumlahKebutuhanKonversi = $jumlahBoM;
                }

                $totalBahan += ($jumlahKebutuhanKonversi * $hargaMaster);
            }

            // Ambil data produk untuk mendapatkan nilai tenaga kerja dan overhead
            $produk = DB::table('produk')->where('id', $produkId)->first();

            if ($produk) {
                // Logika perhitungan overhead berbasis persen (Contoh: 10 / 100)
                $overhead = ($totalBahan + $produk->est_biaya_tenaga) * ($produk->est_biaya_overhead / 100);

                // Total akhir HPP Standar
                $totalHpp = $totalBahan + $overhead + $produk->est_biaya_tenaga;

                // Update otomatis kolom hpp_standar di tabel produk
                DB::table('produk')->where('id', $produkId)->update([
                    'hpp_standar' => $totalHpp,
                    'updated_at'  => now()
                ]);
            }
        }
    }
}
