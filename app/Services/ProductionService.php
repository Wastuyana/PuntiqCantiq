<?php

namespace App\Services;

use App\Models\Produk;
use App\Models\Setting;
use App\Models\Bom;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\StokKritisProduk;
use App\Models\DetailPenjualan;

class ProductionService
{
    /**
     * Logika HPP Standar
     */
    public function hitungHppStandar(Produk $produk)
    {
        $totalBahan = $produk->bom->sum(function ($item) {
            return $item->jumlah_kebutuhan * $item->bahan_baku->harga_satuan;
        });
        $overhead = $totalBahan * ($produk->est_biaya_overhead / 100);
        $totalHpp = $totalBahan + $overhead + $produk->est_biaya_tenaga;

        return $totalHpp;
    }

    /**
     * Logika HPP Aktual per Batch
     */
    public function hitungHppAktual($batch)
    {
        return DB::transaction(function () use ($batch) {
            // Load relasi yang dibutuhkan agar performa kencang
            $batch->load(['batch_bahan.bahan_baku', 'batch_hasil.produk']);

            $totalBiayaBahan = 0;
            $totalBiayaTK = 0;
            $totalBiayaOverhead = 0;
            $biayaPerProduk = [];

            foreach ($batch->batch_bahan as $item) {
                $totalKebutuhanBom = 0;

                // 1. Hitung total kebutuhan teori untuk porsi alokasi
                foreach ($batch->batch_hasil as $hasil) {
                    $bom = BoM::where('produk_id', $hasil->produk_id)
                        ->where('bahan_baku_id', $item->bahan_baku_id)
                        ->first();
                    if ($bom) {
                        $totalKebutuhanBom += $bom->jumlah_kebutuhan * $hasil->hasil_aktual;
                    }
                }

                $hargaTotalBahanAktual = $item->bahan_aktual * $item->bahan_baku->harga_satuan;

                // 2. Alokasi biaya AKTUAL ke porsi masing-masing produk
                foreach ($batch->batch_hasil as $hasil) {
                    $bom = BoM::where('produk_id', $hasil->produk_id)
                        ->where('bahan_baku_id', $item->bahan_baku_id)
                        ->first();

                    if ($bom && $totalKebutuhanBom > 0) {
                        $porsiBiaya = (($bom->jumlah_kebutuhan * $hasil->hasil_aktual) / $totalKebutuhanBom) * $hargaTotalBahanAktual;
                        $biayaPerProduk[$hasil->produk_id] = ($biayaPerProduk[$hasil->produk_id] ?? 0) + $porsiBiaya;
                    }
                }
            }

            // 3. Update rincian per varian di batch_hasil
            foreach ($batch->batch_hasil as $hasil) {
                $totalBahanPerUnit = $hasil->hasil_aktual > 0
                    ? ($biayaPerProduk[$hasil->produk_id] / $hasil->hasil_aktual)
                    : 0;

                $biayaTKPerUnit = $hasil->produk->est_biaya_tenaga ?? 0;
                $biayaOverheadPerUnit = $totalBahanPerUnit * (($hasil->produk->est_biaya_overhead ?? 0) / 100);

                // Akumulasi total batch
                $totalBiayaBahan += $totalBahanPerUnit * $hasil->hasil_aktual;
                $totalBiayaTK += $biayaTKPerUnit * $hasil->hasil_aktual;
                $totalBiayaOverhead += $biayaOverheadPerUnit * $hasil->hasil_aktual;

                $hasil->update([
                    'detail_biaya_bahan' => $totalBahanPerUnit,
                    'detail_biaya_tenagakerja' => $biayaTKPerUnit,
                    'detail_biaya_overhead' => $biayaOverheadPerUnit,
                    'hpp_aktual' => $totalBahanPerUnit + $biayaTKPerUnit + $biayaOverheadPerUnit
                ]);
            }

            // 4. Update ringkasan di tabel Batch
            $batch->update([
                'biaya_bahan'       => $totalBiayaBahan,
                'biaya_tenagakerja' => $totalBiayaTK,
                'biaya_overhead'    => $totalBiayaOverhead,
                'total_biaya'       => $totalBiayaBahan + $totalBiayaTK + $totalBiayaOverhead
            ]);

            return true;
        });
    }

    /**
     * Hitung estimasi biaya tenaga kerja berdasarkan produk dan target produksi
     */
    public function hitungEstimasiTenagaKerja($batch)
    {
        if ($batch->biaya_tenagakerja > 0) {
            return $batch->biaya_tenagakerja;
        }

        $totalEstimasi = 0;
        foreach ($batch->batch_hasil as $hasil) {
            $totalEstimasi += ($hasil->produk->est_biaya_tenaga ?? 0) * $hasil->hasil_target;
        }

        return $totalEstimasi;
    }

    /**
     * Hitung estimasi biaya overhead berdasarkan produk dan target produksi
     */
    public function hitungEstimasiOverhead($batch)
    {
        if ($batch->biaya_overhead > 0) {
            return $batch->biaya_overhead;
        }

        $totalEstimasiOverhead = 0;
        foreach ($batch->batch_hasil as $hasil) {
            $biayaBahanStandar = $hasil->produk->hpp_standar ?? 0;
            $persenOverhead = $hasil->produk->est_biaya_overhead ?? 0;

            $overheadPerUnit = $biayaBahanStandar * ($persenOverhead / 100);
            $totalEstimasiOverhead += $overheadPerUnit * $hasil->hasil_target;
        }

        return $totalEstimasiOverhead;
    }

    /**
     * Logika Generate No Batch Otomatis
     */
    public static function generateNoBatch()
    {
        $date = now()->format('Ymd');
        $count = \App\Models\Batch::whereDate('created_at', now())->count() + 1;
        return "B-{$date}-" . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function updateSafetyStockProduk($produk)
    {
        $leadTime = Setting::where('key', 'lead_time')->value('value') ?? 2;

        // 1. Ambil data penjualan 30 hari terakhir
        $dataPenjualan = \App\Models\DetailPenjualan::where('produk_id', $produk->id)
            ->whereHas('penjualan', function ($q) {
                $q->where('tanggal_penj', '>=', now()->subDays(30));
            })
            ->selectRaw('DATE(created_at) as tanggal, SUM(jumlah_produk) as total')
            ->groupBy('tanggal')
            ->get();

        if ($dataPenjualan->isEmpty()) {
            return false; // Beri sinyal ke controller kalau data kosong
        }

        // 2. Hitung d (rata-rata) dan dmax (maksimal harian)
        $d = $dataPenjualan->avg('total');
        $dmax = $dataPenjualan->max('total');

        // 3. Hitung rumus Safety Stock & Reorder Point
        $safetyStock = ($dmax - $d) * $leadTime;
        $batasMinimal = ($d * $leadTime) + $safetyStock;

        // 4. Update data ke database
        return $produk->update([
            'safety_stok' => ceil($batasMinimal)
        ]);
    }

    /**
     * Logika Cek Stok Kritis & Notifikasi
     */
    public function cekStokKritis($produk)
    {
        $stokSekarang = (int) $produk->stok;
        $stokMinimal = (int) $produk->safety_stok;

        if ($stokSekarang <= $stokMinimal) {
            $users = User::all();

            foreach ($users as $user) {
                $alreadyNotified = $user->unreadNotifications()
                    ->whereJsonContains('data->id_produk', $produk->id)
                    ->exists();

                if (!$alreadyNotified) {
                    $user->notify(new StokKritisProduk($produk));
                }
            }
        }
    }

    public function getDailyAverageSales($produk, $hari = 30)
    {
        // Memanggil fungsi static dari model DetailPenjualan
        $total = DetailPenjualan::getTotalPenjualan($produk->id, $hari);

        return $total > 0 ? $total / $hari : 0;
    }

    /**
     * Logika Rekomendasi Produksi
     */
    public function getRekomendasiProduksi()
    {
        $tInterval = Setting::where('key', 't_interval')->value('value') ?? 7;
        $kapasitasMax = Setting::where('key', 'kapasitas_produksi')->value('value') ?? 100;

        $produks = Produk::all();
        $daftarRekomendasi = [];

        foreach ($produks as $p) {
            $dAvg = $this->getDailyAverageSales($p, 30);
            $sMax = ($dAvg * $tInterval) + $p->safety_stok;
            $qRec = ceil($sMax) - $p->stok;
            $prioritas = $dAvg > 0 ? ($p->stok / $dAvg) : 999;

            $daftarRekomendasi[] = [
                'id' => $p->id,
                'nama' => $p->kategori . ' - ' . $p->varian,
                'stok_aktual' => $p->stok,
                'safety_stock' => $p->safety_stok,
                'd_avg' => round($dAvg, 2),
                'q_rec' => max($qRec, 0),
                'prioritas' => $prioritas,
            ];
        }

        $daftarSorted = collect($daftarRekomendasi)->sortBy('prioritas');

        $kapasitasTersisa = $kapasitasMax;
        $daftarFinal = [];
        $totalKebutuhanBahan = [];
        $satuanBahan = [];

        foreach ($daftarSorted as $item) {
            if ($kapasitasTersisa >= $item['q_rec']) {
                $item['jumlah_acc'] = $item['q_rec'];
                $kapasitasTersisa -= $item['q_rec'];
            } else {
                $item['jumlah_acc'] = $kapasitasTersisa;
                $kapasitasTersisa = 0;
            }
            $daftarFinal[] = $item;

            if ($item['jumlah_acc'] > 0) {
                $produkWithBom = Produk::with('bom.bahan_baku')->find($item['id']);
                if ($produkWithBom && $produkWithBom->bom) {
                    foreach ($produkWithBom->bom as $bom) {
                        $namaBahan = $bom->bahan_baku->nama;
                        $satuan = $bom->bahan_baku->satuan;
                        $kebutuhan = $bom->jumlah_kebutuhan * $item['jumlah_acc'];
                        $stokBahanAktual = $bom->bahan_baku->stok;

                        if (!isset($totalKebutuhanBahan[$namaBahan])) {
                            $totalKebutuhanBahan[$namaBahan] = 0;
                            $satuanBahan[$namaBahan] = $satuan;
                            $stokBahan[$namaBahan] = $stokBahanAktual;
                        }
                        $totalKebutuhanBahan[$namaBahan] += $kebutuhan;
                    }
                }
            }
        }

        return [
            'batchAktif' => collect($daftarFinal)->where('jumlah_acc', '>', 0),
            'daftarTunggu' => collect($daftarFinal)->where('jumlah_acc', '==', 0)->where('q_rec', '>', 0),
            'totalKebutuhanBahan' => $totalKebutuhanBahan,
            'satuanBahan' => $satuanBahan,
            'stokBahan' => $stokBahan ?? [],
            'kapasitasMax' => $kapasitasMax
        ];
    }
}
