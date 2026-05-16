<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\PenyesuaianStok;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use App\Services\ProductionService;

class PenyesuaianStokController extends Controller
{
    protected $productionService;
    
    public function __construct(ProductionService $productionService)
    {
        $this->productionService = $productionService;
    }

     /**
     * Show the form for creating a new resource.
     */

    public function create($batch_id)
    {
        $batch = Batch::with('batch_hasil.produk')->findOrFail($batch_id);

        return view('admin.produksi.penyesuaian_stok', compact('batch'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'batch_id' => 'required|exists:batch,id',
            'produk_id' => 'required|exists:produk,id',
            'jumlah_rusak' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:45',
        ]);

        return DB::transaction(function () use ($request) {
            PenyesuaianStok::create([
                'batch_id' => $request->batch_id,
                'produk_id' => $request->produk_id,
                'jumlah_rusak' => $request->jumlah_rusak,
                'keterangan' => $request->keterangan,
            ]);

            $produk = Produk::findOrFail($request->produk_id);
            $produk->decrement('stok', $request->jumlah_rusak);

            $produk->refresh();
            $this->productionService->cekStokKritis($produk);

            return redirect()->route('admin.produksi.batch.index')
                ->with('success', 'Laporan stok rusak berhasil diproses dan stok produk telah diperbarui.');
        });
    }
}
