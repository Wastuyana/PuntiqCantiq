<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Services\ProductionService;

class BahanBakuController extends Controller
{
    protected $prodService;

    public function __construct(ProductionService $prodService)
    {
        $this->prodService = $prodService;
    }

    public function index()
    {
        $bahanBakus = BahanBaku::all();
        return view('owner.inventory.bahan_baku', compact('bahanBakus'));
    }

    public function hitungUlang($id)
    {
        $bb = BahanBaku::findOrFail($id);
        $result = $this->prodService->updateSafetyStockBahan($bb);

        if ($result === false) {
            return redirect()->back()->with('error', 'Gagal: Data produksi 30 hari terakhir untuk bahan ini belum tersedia.');
        }

        return redirect()->back()->with('success', "SS & ROP {$bb->nama} berhasil dihitung ulang!");
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:250',
            'satuan' => 'required',
            'harga_satuan' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        $bahanExisting = BahanBaku::where('nama', $request->nama)->first();

        if ($bahanExisting) {
            $bahanExisting->stok += $request->stok;
            $bahanExisting->harga_satuan = $request->harga_satuan;
            $bahanExisting->harga_updated_at = now();
            $bahanExisting->save();

            return redirect()->back()->with('success', "Stok {$request->nama} berhasil ditambah & harga diupdate!");
        }

        $count = BahanBaku::count() + 1;
        $kode = 'BB-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        BahanBaku::create([
            'kode_bahan' => $kode,
            'nama' => $request->nama,
            'satuan' => $request->satuan,
            'harga_satuan' => $request->harga_satuan,
            'stok' => $request->stok,
            'ss_bahan' => 0, // Default awal
            'rop_bahan' => 0,          // Default awal
            'harga_updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Bahan baku baru berhasil ditambah!');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|max:250|unique:bahan_baku,nama,' . $id, 
            'satuan' => 'required', 
            'harga_satuan' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        $bb = BahanBaku::findOrFail($id);

        $bb->update([
            'nama' => $request->nama,
            'satuan' => $request->satuan, 
            'harga_satuan' => $request->harga_satuan,
            'stok' => $request->stok,
            'harga_updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $bb = BahanBaku::findOrFail($id);
        $bb->delete();
        return redirect()->back()->with('success', 'Bahan baku berhasil dihapus!');
    }
}