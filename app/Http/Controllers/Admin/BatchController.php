<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Batch;
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
    public function index()
    {
        $batches = Batch::with(['batch_hasil'])
            ->latest()->get();

        $produks = Produk::has('bom')
            ->with('bom.bahan_baku')
            ->get();

        $generatedNoBatch = $this->productionService->generateNoBatch();

        return view('admin.produksi.batch', compact('batches', 'produks', 'generatedNoBatch'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $batch = Batch::with(['batch_hasil.produk', 'batch_bahan.bahan_baku', 'user'])
            ->findOrFail($id);

        return view('admin.produksi.batch_detail', compact('batch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, ProductionService $productionService)
    {
        $batch = Batch::findOrFail($id);

        if ($batch->status == 'completed') {
            return redirect()->route('admin.produksi.batch.index')
                ->with('error', 'Batch ini sudah selesai dan tidak bisa diubah lagi.');
        }

        $estimasiTK = $productionService->hitungEstimasiTenagaKerja($batch);
        $estimasiOverhead = $productionService->hitungEstimasiOverhead($batch);

        return view('admin.produksi.batch_edit', compact('batch', 'estimasiTK', 'estimasiOverhead'));
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

            return redirect()->route('admin.produksi.batch.index')->with('success', 'Produksi Selesai!');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
