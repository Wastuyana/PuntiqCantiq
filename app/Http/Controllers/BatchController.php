<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\BatchBahan;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use App\Services\ProductionService;

class BatchController extends Controller
{
    protected $productionService;

    public function __construct(ProductionService $productionService)
    {
        $this->productionService = $productionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        $batches = Batch::with(['batch_hasil'])->orderBy('tanggal_produksi', 'desc')
            ->whereMonth('tanggal_produksi', $bulan)
            ->whereYear('tanggal_produksi', $tahun)
            ->latest()->get();

        // $produks = Produk::has('bom')
        //     ->with('bom.bahan_baku')
        //     ->get();

        $generatedNoBatch = $this->productionService->generateNoBatch();

        return view('owner.produksi.batch', compact('batches', 'generatedNoBatch'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $batches = Batch::with(['batch_hasil'])
            ->latest()->get();

        $produks = Produk::has('bom')
            ->with('bom.bahan_baku')
            ->get();

        $generatedNoBatch = $this->productionService->generateNoBatch();
        $rekomendasiTarget = $request->input('hasil_target', []);
        $rekomendasiIds = $request->input('produk_ids', []);

        return view('owner.produksi.batch_create', compact('batches', 'produks', 'generatedNoBatch', 'rekomendasiIds', 'rekomendasiTarget'));
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
            $generatedNoBatch = $this->productionService->generateNoBatch();
            $batch = Batch::create([
                'nomor_batch'      => $generatedNoBatch,
                'user_id'          => \Illuminate\Support\Facades\Auth::id(),
                'tanggal_produksi' => $request->tanggal_produksi,
                'status'           => $request->status,
                'checklist_sop'    => 0,
                'sop_details'      => null,
                'biaya_tenagakerja' => 0,
                'biaya_bahan'       => 0,
                'biaya_overhead'    => 0,
                'total_biaya'       => 0,
            ]);

            foreach ($request->produk_ids as $produkId) {
                $target = $request->hasil_target[$produkId] ?? 0;

                if ($target <= 0) continue;

                $produk = Produk::with('bom.bahan_baku')->findOrFail($produkId);


                $batch->batch_hasil()->create([
                    'produk_id'             => $produkId,
                    'hasil_target'          => $target,
                    'hasil_aktual'          => 0,
                    'detail_biaya_bahan'    => 0,
                    'detail_biaya_tenagakerja' => 0,
                    'detail_biaya_overhead' => 0,
                    'hpp_aktual'            => 0,
                ]);

                foreach ($produk->bom as $item) {
                    $totalButuh = $item->jumlah_kebutuhan * $target;

                    $batchBahan = $batch->batch_bahan()->firstOrNew([
                        'bahan_baku_id' => $item->bahan_baku_id
                    ]);

                    $batchBahan->bahan_target = ($batchBahan->bahan_target ?? 0) + $totalButuh;
                    $batchBahan->bahan_aktual = 0;
                    $batchBahan->save();

                    if ($request->status == 'selesai') {
                        $item->bahan_baku->decrement('stok', $totalButuh);
                    }
                }
            }

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
    public function edit(string $id, ProductionService $productionService)
    {
        $batch = Batch::findOrFail($id);

        if ($batch->status == 'completed') {
            return redirect()->route('owner.produksi.batch.index')
                ->with('error', 'Batch ini sudah selesai dan tidak bisa diubah lagi.');
        }

        $estimasiTK = $productionService->hitungEstimasiTenagaKerja($batch);
        $estimasiOverhead = $productionService->hitungEstimasiOverhead($batch);

        return view('owner.produksi.batch_edit', compact('batch', 'estimasiTK', 'estimasiOverhead'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, ProductionService $productionService)
    {
        $batch = Batch::findOrFail($id);

        // Simpan status lama sebelum diupdate untuk pengecekan stok
        $statusLama = $batch->status;

        return DB::transaction(function () use ($request, $batch, $statusLama, $productionService) {
            // 1. Update hasil produksi & Tambah stok produk jadi
            foreach ($request->hasil_aktual as $hasilId => $nilaiAktual) {
                $batchHasil = $batch->batch_hasil()->find($hasilId);
                if ($batchHasil) {
                    $batchHasil->update(['hasil_aktual' => $nilaiAktual]);
                    $batchHasil->produk->increment('stok', $nilaiAktual);
                }
            }

            // 2. Update pemakaian bahan & Kurangi stok bahan baku
            foreach ($request->bahan_aktual as $bahanId => $nilaiBahan) {
                $batchBahan = $batch->batch_bahan()->find($bahanId);
                if ($batchBahan) {
                    $batchBahan->update(['bahan_aktual' => $nilaiBahan]);

                    if ($statusLama === 'draft') {
                        $batchBahan->bahan_baku->decrement('stok', $nilaiBahan);
                    }
                }
            }

            // 3. Simpan biaya & selesaikan batch
            $batch->update([
                'status' => 'selesai',
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
                'biaya_tenagakerja' => $request->biaya_tenagakerja,
                'biaya_overhead' => $request->biaya_overhead,
                'checklist_sop' => 1,
                'sop_details' => $request->sop_details,
            ]);

            // 4. Kalkulasi HPP Aktual
            $productionService->hitungHppAktual($batch);

            return redirect()->route('owner.produksi.batch.index')->with('success', 'Produksi Selesai!');
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
