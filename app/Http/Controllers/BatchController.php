<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\BatchBahan;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batches = Batch::with(['batch_hasil'])
            ->latest()->get();

        $produks = Produk::has('bom')
            ->with('bom.bahan_baku')
            ->get();

        $generatedNoBatch = Batch::generateNoBatch();

        return view('owner.produksi.batch', compact('batches', 'produks', 'generatedNoBatch'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $batches = Batch::with(['batch_hasil'])
            ->latest()->get();

        $produks = Produk::has('bom')
            ->with('bom.bahan_baku')
            ->get();

        $generatedNoBatch = Batch::generateNoBatch();

        return view('owner.produksi.batch_create', compact('batches', 'produks', 'generatedNoBatch'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'produk_ids' => 'required|array',
            'hasil_target' => 'required|array',
            'status' => 'required|in:draft,selesai',
            'tanggal_produksi' => 'required|date',
        ]);

        return DB::transaction(function () use ($request) {
            $batch = Batch::create([
                'nomor_batch'      => Batch::generateNoBatch(),
                'user_id'          => \Illuminate\Support\Facades\Auth::id(),
                'tanggal_produksi' => $request->tanggal_produksi,
                'biaya_tenagakerja' => 0,
                'biaya_overhead'    => 0,
                'status'            => $request->status,
                'total_biaya'       => 0,
            ]);

            $totalTenagaKerja = 0;
            $totalOverhead = 0;

            foreach ($request->produk_ids as $produkId) {
                $target = $request->hasil_target[$produkId] ?? 0;

                if ($target <= 0) continue;

                $produk = Produk::with('bom.bahan_baku')->findOrFail($produkId);

                $totalTenagaKerja += $produk->est_biaya_tenaga ?? 0;
                $totalOverhead += $produk->est_biaya_overhead ?? 0;

                $batch->batch_hasil()->create([
                    'produk_id'    => $produkId,
                    'hasil_target' => $target,
                    'hasil_aktual' => 0,
                    'hpp_aktual'   => 0,
                ]);

                foreach ($produk->bom as $item) {
                    $totalButuh = $item->jumlah_kebutuhan * $target;

                    $batchBahan = $batch->batch_bahan()->firstOrNew([
                        'bahan_baku_id' => $item->bahan_baku_id
                    ]);

                    $batchBahan->bahan_target = ($batchBahan->bahan_target ?? 0) + $totalButuh;
                    $batchBahan->bahan_aktual = 0;
                    $batchBahan->save();

                    // 4. Jika langsung selesai, potong stok
                    if ($request->status == 'selesai') {
                        $item->bahan_baku->decrement('stok', $totalButuh);
                    }
                }
            }

            $batch->update([
                'biaya_tenagakerja' => $totalTenagaKerja,
                'biaya_overhead'    => $totalOverhead,
            ]);

            return redirect()->route('owner.produksi.batch.index')->with('success', 'Data Batch berhasil disimpan!');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $batch = Batch::with(['batch_hasil.produk', 'batch_bahan.bahan_baku', 'user'])
            ->findOrFail($id);

        return view('owner.produksi.batch_detail', compact('batch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $batch = Batch::findOrFail($id);

        if ($batch->status == 'completed') {
            return redirect()->route('owner.produksi.batch.index')
                ->with('error', 'Batch ini sudah selesai dan tidak bisa diubah lagi.');
        }

        return view('owner.produksi.batch_edit', compact('batch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);

        return DB::transaction(function () use ($request, $batch) {
            foreach ($request->hasil_aktual as $hasilId => $nilaiAktual) {
                $batchHasil = $batch->batch_hasil()->find($hasilId);
                if ($batchHasil) {
                    $batchHasil->update(['hasil_aktual' => $nilaiAktual]);

                    $batchHasil->produk->increment('stok', $nilaiAktual);
                }
            }

            foreach ($request->bahan_aktual as $bahanId => $nilaiBahan) {
                $batchBahan = $batch->batch_bahan()->find($bahanId);
                if ($batchBahan) {
                    $batchBahan->update(['bahan_aktual' => $nilaiBahan]);
                }
            }

            $batch->update([
                'status' => 'selesai',
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            ]);

            $batch->hitungHPPHppAktual();

            return redirect()->route('owner.produksi.batch.index')
                ->with('success', 'Produksi Multivarian Selesai & HPP Otomatis Terhitung!');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $batch = Batch::findOrFail($id);

        if ($batch->status == 'selesai') {
            return redirect()->back()->with('error', 'Batch yang sudah selesai tidak bisa dihapus!');
        }

        $batch->delete();

        return redirect()->back()->with('success', 'Data Batch berhasil dihapus!');
    }
}
