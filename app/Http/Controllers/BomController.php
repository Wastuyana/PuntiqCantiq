<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bom;
use App\Models\Produk;
use App\Services\ProductionService;

class BomController extends Controller
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
        $produks = Produk::with('bom.bahan_baku')->orderby('kategori')->get();

        return view('owner.master.bom', compact('produks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ProductionService $productionService)
    {
        $produk = Produk::findOrFail($request->produk_id);
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_baku,id',
            'jumlah_kebutuhan' => 'required|numeric|min:0.0001',
        ]);

        Bom::create([
            'produk_id' => $produk->id,
            'bahan_baku_id' => $request->bahan_baku_id,
            'jumlah_kebutuhan' => $request->jumlah_kebutuhan,
        ]);

        $hppTerbaru = $productionService->hitungHppStandar($produk->fresh());

        $produk->update(['hpp_standar' => $hppTerbaru]);

        return redirect()->back()->with('success', 'Bahan baku berhasil ditambahkan ke resep!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk = Produk::with('bom.bahan_baku')->findOrFail($id);
        $allBahanBaku = \App\Models\BahanBaku::all();
        return view('owner.master.detail_bom', compact('produk', 'allBahanBaku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah_kebutuhan' => 'required|numeric|min:0.0001',
        ]);

        $bom = Bom::findOrFail($id);
        $bom->update([
            'jumlah_kebutuhan' => $request->jumlah_kebutuhan
        ]);

        $produk = $bom->produk;
        $hppTerbaru = $this->productionService->hitungHppStandar($produk->fresh());
        $produk->update(['hpp_standar' => $hppTerbaru]);

        return redirect()->back()->with('success', 'Komposisi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Bom::destroy($id);
        return back()->with('success', 'Bahan berhasil dihapus!');
    }
}
