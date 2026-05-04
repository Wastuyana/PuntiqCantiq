<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Setting;
use App\Http\Controllers\BatchController;
use App\Models\Batch;

class RekomendasiProdController extends Controller
{
    public function rekomendasiProduksi()
    {
        $tInterval = Setting::where('key', 't_interval')->value('value') ?? 7;
        $kapasitasMax = Setting::where('key', 'kapasitas_produksi')->value('value') ?? 100;

        $produks = Produk::all();
        $daftarRekomendasi = [];

        foreach ($produks as $p) {
            $dAvg = $p->getDavg();
            $sMax = ($dAvg * $tInterval) + $p->safety_stok;
            $qRec = ceil($sMax) - $p->stok;
            $prioritas = $dAvg > 0 ? ($p->stok / $dAvg) : 999;

            $daftarRekomendasi[] = [
                'id' => $p->id,
                'nama' => $p->kategori . ' - ' . $p->varian,
                'stok_aktual' => $p->stok,
                'd_avg' => round($dAvg, 2),
                'q_rec' => $qRec > 0 ? $qRec : 0,
                'prioritas' => $prioritas,
            ];
        }

        $daftarRekomendasi = collect($daftarRekomendasi)->sortBy('prioritas');

        $kapasitasTersisa = $kapasitasMax;
        $daftarFinal = [];
        $totalKebutuhanBahan = [];
        $satuanBahan = [];

        foreach ($daftarRekomendasi as $item) {
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

                        if (!isset($totalKebutuhanBahan[$namaBahan])) {
                            $totalKebutuhanBahan[$namaBahan] = 0;
                            $satuanBahan[$namaBahan] = $satuan;
                        }
                        $totalKebutuhanBahan[$namaBahan] += $kebutuhan;
                    }
                }
            }
        }

        $batchAktif = collect($daftarFinal)->where('jumlah_acc', '>', 0);
        $daftarTunggu = collect($daftarFinal)->where('jumlah_acc', '==', 0)->where('q_rec', '>', 0);

        $generatedNoBatch = Batch::generateNoBatch();

        return view('owner.produksi.rekomendasi', compact(
            'batchAktif',
            'daftarTunggu',
            'totalKebutuhanBahan',
            'satuanBahan',
            'kapasitasMax',
            'generatedNoBatch'
        ));
    }

    public function storeKeBatch(Request $request)
    {
        return (new BatchController)->store($request);
    }
}
