<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bom;
use App\Models\Produk;
use App\Models\BahanBaku;

class BomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $produk = Produk::findOrFail($request->produk_id);
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_baku,id',
            'jumlah_kebutuhan' => 'required|numeric|min:0.001',
        ]);

        Bom::create([
            'produk_id' => $produk->id,
            'bahan_baku_id' => $request->bahan_baku_id,
            'jumlah_kebutuhan' => $request->jumlah_kebutuhan,
        ]);

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
