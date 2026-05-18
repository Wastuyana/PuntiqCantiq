<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahanBakus = \App\Models\BahanBaku::all();
        return view('owner.inventory.bahan_baku', compact('bahanBakus'));
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
        $request->validate([
            'nama' => 'required|max:250',
            'satuan' => 'required',
            'harga_satuan' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        $bahanExisting = \App\Models\BahanBaku::where('nama', $request->nama)->first();

        if ($bahanExisting) {
            $bahanExisting->stok_bahan += $request->stok;
            $bahanExisting->harga_satuan = $request->harga_satuan;
            $bahanExisting->save();

            return redirect()->back()->with('success', "Stok {$request->nama} berhasil ditambahkan ke data yang sudah ada!");
        }

        $count = \App\Models\BahanBaku::count() + 1;
        $kode = 'BB-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        \App\Models\BahanBaku::create([
            'kode_bahan' => $kode,
            'nama' => $request->nama,
            'satuan' => $request->satuan,
            'harga_satuan' => $request->harga_satuan,
            'stok' => $request->stok,
            'ss_bahan' => 0,
            'rop_bahan' => 0,
        ]);

        return redirect()->back()->with('success', 'Bahan baku baru berhasil ditambah!');
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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|max:250|unique:bahan_baku,nama,' . $id . ',id',
            'satuan' => 'required',
            'harga_satuan' => 'required|numeric',
            'stok' => 'required|numeric',
        ], [
            // Pesan error kustom agar lebih user-friendly
            'nama.unique' => 'Gagal! Bahan baku dengan nama "' . $request->nama . '" sudah ada di daftar.',
        ]);

        // 2. Ambil data dan update
        $bb = BahanBaku::findOrFail($id);

        // Menggunakan update manual agar lebih aman
        $bb->update([
            'nama' => $request->nama,
            'satuan' => $request->satuan,
            'harga_satuan' => $request->harga_satuan,
            'stok' => $request->stok,
        ]);

        return redirect()->back()->with('success', 'Bahan baku berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bb = BahanBaku::findOrFail($id);
        $bb->delete();

        return redirect()->back()->with('success', 'Bahan baku berhasil dihapus!');
    }
}
