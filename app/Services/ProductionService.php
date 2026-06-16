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
            $jumlahBoM = $item->jumlah_kebutuhan;
            $satuanMaster = strtolower($item->bahan_baku->satuan);
            $hargaMaster = $item->bahan_baku->harga_satuan;

            if (in_array($satuanMaster, ['kg', 'liter', 'l'])) {
                $jumlahKebutuhanKonversi = $jumlahBoM / 1000;
            } else {
                $jumlahKebutuhanKonversi = $jumlahBoM;
            }

            return $jumlahKebutuhanKonversi * $hargaMaster;
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
                $satuanMaster = strtolower($item->bahan_baku->satuan);

                // 1. Hitung total kebutuhan teori untuk porsi alokasi
                foreach ($batch->batch_hasil as $hasil) {
                    $bom = BoM::where('produk_id', $hasil->produk_id)
                        ->where('bahan_baku_id', $item->bahan_baku_id)
                        ->first();

                    if ($bom) {
                        $kebutuhanPerUnit = in_array($satuanMaster, ['kg', 'liter', 'l'])
                            ? ($bom->jumlah_kebutuhan / 1000)
                            : $bom->jumlah_kebutuhan;

                        $totalKebutuhanBom += $kebutuhanPerUnit * $hasil->hasil_aktual;
                    }
                }

                $hargaTotalBahanAktual = $item->bahan_aktual * $item->bahan_baku->harga_satuan;

                // 2. Alokasi biaya AKTUAL ke porsi masing-masing produk
                foreach ($batch->batch_hasil as $hasil) {
                    $bom = BoM::where('produk_id', $hasil->produk_id)
                        ->where('bahan_baku_id', $item->bahan_baku_id)
                        ->first();

                    if ($bom && $totalKebutuhanBom > 0) {
                        $kebutuhanPerUnit = in_array($satuanMaster, ['kg', 'liter', 'l'])
                            ? ($bom->jumlah_kebutuhan / 1000)
                            : $bom->jumlah_kebutuhan;

                        // Hitung porsi biaya berdasarkan bobot teori yang sudah sama-sama berskala kg/liter
                        $porsiBiaya = (($kebutuhanPerUnit * $hasil->hasil_aktual) / $totalKebutuhanBom) * $hargaTotalBahanAktual;

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
        $leadTime = Setting::where('key', 'lead_time')->value('value') ?? 4;

        $dataPenjualan = \App\Models\DetailPenjualan::where('produk_id', $produk->id)
            ->whereHas('penjualan', function ($q) {
                $q->where('tanggal_penj', '>=', now()->subDays(30));
            })
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->selectRaw('DATE(penjualan.tanggal_penj) as tanggal, SUM(detail_penjualan.jumlah_produk) as total')
            ->groupBy('tanggal')
            ->get();

        if ($dataPenjualan->isEmpty()) {
            return false;
        }

        $totalTerjualSebulan = $dataPenjualan->sum('total');
        $d = $totalTerjualSebulan / 30;

        $dmax = $dataPenjualan->max('total');

        $safetyStock = ($dmax - $d) * $leadTime;
        $batasMinimal = ($d * $leadTime) + $safetyStock;

        return $produk->update([
            'ss_produk' => ceil($safetyStock),
            'rop_produk' => ceil($batasMinimal)
        ]);
    }

    /**
     * Logika Cek Stok Kritis & Notifikasi
     */
    public function cekStokKritis($produk)
    {
        $stokSekarang = (int) $produk->stok;
        $stokMinimal = (int) $produk->rop_produk;

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
        // Ambil data penjualan per tanggal yang ada transaksinya dalam rentang waktu x hari terakhir
        $dataPenjualan = \App\Models\DetailPenjualan::where('produk_id', $produk->id)
            ->whereHas('penjualan', function ($q) use ($hari) {
                $q->where('tanggal_penj', '>=', now()->subDays($hari));
            })
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->selectRaw('DATE(penjualan.tanggal_penj) as tanggal, SUM(detail_penjualan.jumlah_produk) as total')
            ->groupBy('tanggal')
            ->get();

        // Jika dalam x hari terakhir sama sekali tidak pernah terjual, return 0 (bukan false)
        if ($dataPenjualan->isEmpty()) {
            return 0;
        }

        // Mengambil nilai rata-rata dari total penjualan harian yang aktif
        return $dataPenjualan->avg('total');
    }

    /**
     * Logika Rekomendasi Produksi
     */
    public function getRekomendasiProduksi()
    {
        $tInterval = Setting::where('key', 't_interval')->value('value') ?? 7;
        $kapasitasMax = Setting::where('key', 'kapasitas_produksi')->value('value') ?? 100;

        // Perbaikan Performa: Ambil produk SEKALIGUS dengan relasi BoM di awal (Eager Loading)
        $produks = Produk::with('bom.bahan_baku')->get();
        $daftarRekomendasi = [];

        foreach ($produks as $p) {
            $dAvg = $this->getDailyAverageSales($p, 30);
            $rop = $p->rop_produk;

            // 1. Filter: Hanya ambil produk di bawah ROP
            if ($p->stok < $rop) {
                $sMax = ($dAvg * $tInterval) + $p->ss_produk;
                $qRec = ceil($sMax) - $p->stok;
                $prioritas = $dAvg > 0 ? ($p->stok / $dAvg) : 999;

                if ($qRec > 0) {
                    $daftarRekomendasi[] = [
                        'id' => $p->id,
                        'produk_obj' => $p, // Simpan objek produk agar tidak perlu query ulang nanti
                        'nama' => $p->kategori . ' - ' . $p->varian,
                        'stok_aktual' => $p->stok,
                        'ss_produk' => $p->ss_produk,
                        'rop_produk' => $rop,
                        'd_avg' => round($dAvg, 2),
                        'q_rec' => $qRec,
                        'prioritas' => $prioritas,
                    ];
                }
            }
        }

        $daftarSorted = collect($daftarRekomendasi)->sortBy('prioritas');

        $kapasitasTersisa = $kapasitasMax;
        $batchAktif = [];
        $daftarTunggu = [];
        $totalKebutuhanBahan = [];
        $satuanBahan = [];
        $stokBahan = [];

        foreach ($daftarSorted as $item) {
            if ($kapasitasTersisa > 0) {
                if ($kapasitasTersisa >= $item['q_rec']) {
                    // Kapasitas cukup untuk memenuhi semua kebutuhan produk ini
                    $item['jumlah_acc'] = $item['q_rec'];
                    $kapasitasTersisa -= $item['q_rec'];
                    $batchAktif[] = $item;
                } else {
                    // Perbaikan Logika: Ambil sisa kapasitas yang tersedia (dicicil), sisanya masuk daftar tunggu
                    $item['jumlah_acc'] = $kapasitasTersisa;

                    $sisaBelumTercukupi = $item['q_rec'] - $kapasitasTersisa;
                    $kapasitasTersisa = 0; // Kapasitas pabrik sekarang habis

                    $batchAktif[] = $item;

                    // Masukkan sisa yang tidak cukup ke daftar tunggu
                    $itemTunggu = $item;
                    $itemTunggu['q_rec'] = $sisaBelumTercukupi;
                    $itemTunggu['jumlah_acc'] = 0;
                    $daftarTunggu[] = $itemTunggu;
                }
            } else {
                // Kapasitas sudah benar-benar habis, langsung masuk daftar tunggu
                $item['jumlah_acc'] = 0;
                $daftarTunggu[] = $item;
            }

            // Hitung bahan baku menggunakan objek yang sudah di-load di awal (Tanpa query ulang!)
            if ($item['jumlah_acc'] > 0) {
                $produkWithBom = $item['produk_obj'];
                if ($produkWithBom && $produkWithBom->bom) {
                    foreach ($produkWithBom->bom as $bom) {
                        $namaBahan = $bom->bahan_baku->nama;
                        $satuan = strtolower($bom->bahan_baku->satuan);
                        $stokBahanAktual = $bom->bahan_baku->stok;

                        $kebutuhanTeori = $bom->jumlah_kebutuhan * $item['jumlah_acc'];
                        $kebutuhan = in_array($satuan, ['kg', 'liter', 'l']) ? ($kebutuhanTeori / 1000) : $kebutuhanTeori;

                        if (!isset($totalKebutuhanBahan[$namaBahan])) {
                            $totalKebutuhanBahan[$namaBahan] = 0;
                            $satuanBahan[$namaBahan] = $bom->bahan_baku->satuan;
                            $stokBahan[$namaBahan] = $stokBahanAktual;
                        }

                        $totalKebutuhanBahan[$namaBahan] += $kebutuhan;
                    }
                }
            }
        }

        return [
            'batchAktif'          => collect($batchAktif),
            'daftarTunggu'        => collect($daftarTunggu),
            'totalKebutuhanBahan' => collect($totalKebutuhanBahan)->map(fn($v) => round($v, 2))->toArray(),
            'satuanBahan'         => $satuanBahan,
            'stokBahan'           => $stokBahan,
            'kapasitasMax'        => $kapasitasMax
        ];
    }

    public function updateSafetyStockBahan($bahanBaku)
    {
        $leadTimes = \App\Models\BahanMasuk::where('bahan_baku_id', $bahanBaku->id)
            ->whereNotNull('tanggal_masuk')
            ->get()
            ->map(function ($item) {
                return \Carbon\Carbon::parse($item->tanggal_pesan)
                    ->diffInDays(\Carbon\Carbon::parse($item->tanggal_masuk));
            });

        $avgLeadTime = $leadTimes->isEmpty() ? 2 : $leadTimes->avg();

        $dataPemakaian = \App\Models\BatchBahan::join('batch', 'batch_bahan.batch_id', '=', 'batch.id')
            ->where('batch_bahan.bahan_baku_id', $bahanBaku->id)
            ->where('batch.tanggal_produksi', '>=', now()->subDays(30)) 
            ->selectRaw('DATE(batch.tanggal_produksi) as tanggal, SUM(batch_bahan.bahan_aktual) as total')
            ->groupBy('tanggal')
            ->get();

        if ($dataPemakaian->isEmpty()) return false;

        if ($dataPemakaian->isEmpty()) return false;

        $d = $dataPemakaian->avg('total');      
        $dmax = $dataPemakaian->max('total');   
        $safetyStock = ($dmax - $d) * $avgLeadTime;
        $rop = ($d * $avgLeadTime) + $safetyStock;

        return $bahanBaku->update([
            'ss_bahan' => max(0, ceil($safetyStock)),
            'rop_bahan' => ceil($rop)
        ]);
    }
}
