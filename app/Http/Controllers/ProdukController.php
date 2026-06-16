<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Services\ProductionService;

class ProdukController extends Controller
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
        $produks = Produk::orderBy('kategori')->get();

        $daftarKategori = Produk::distinct()->pluck('kategori');

        foreach ($produks as $produk) {
            $this->productionService->updateSafetyStockProduk($produk);
        }

        return view('owner.master.produk', compact('produks', 'daftarKategori'));
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
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'kategori' => 'required',
            'varian' => 'required',
            'ukuran' => 'required',
            'stok' => 'required|integer|min:0',
        ]);

        Produk::create([
            'kategori' => $request->kategori,
            'varian' => $request->varian,
            'ukuran' => $request->ukuran,
            'stok' => $request->stok,
            'ss_produk' => $request->ss_produk ?? 0,
            'est_biaya_tenaga' => $request->est_biaya_tenaga ?? 0,
            'est_biaya_overhead' => $request->est_biaya_overhead ?? 0,
            'hpp_standar' => 0,
            'harga_jual' => $request->harga_jual ?? 0,
        ]);

        return redirect()->route('owner.master.produk.index')->with('success', 'Data Produk Berhasil Dicatat!');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|string',
            'varian'   => 'required|string',
            'ukuran'   => 'required|string',
            'stok'     => 'required|integer|min:0',
            'est_biaya_tenaga' => 'required|numeric|min:0',
            'est_biaya_overhead' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update([
            'kategori' => $request->kategori,
            'varian'   => $request->varian,
            'ukuran'   => $request->ukuran,
            'stok'     => $request->stok,
            'est_biaya_tenaga' => $request->est_biaya_tenaga,
            'est_biaya_overhead' => $request->est_biaya_overhead,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect()->back()->with('success', 'Data produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        $namaVarian = $produk->varian;

        $produk->delete();

        return redirect()->back()->with('success', 'Produk "' . $namaVarian . '" telah dihapus dari sistem.');
    }

    public function indexBom()
    {
        $produks = Produk::with('bom.bahan_baku')->orderby('kategori')->get();

        return view('owner.master.bom', compact('produks'));
    }

    public function updateStokMinimal($id, ProductionService $productionService)
    {
        $produk = Produk::findOrFail($id);
        $success = $productionService->updateSafetyStockProduk($produk);

        if (!$success) {
            return back()->with('error', 'Data penjualan 30 hari terakhir tidak ditemukan.');
        }

        return back()->with('success', "Batas stok minimal {$produk->kategori}-{$produk->varian}-{$produk->ukuran} berhasil diperbarui!");
    }
}
