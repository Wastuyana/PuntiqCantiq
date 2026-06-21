<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierOwnerController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        $bahanBakus = \App\Models\BahanBaku::all();
        return view('owner.partner.supplier', compact('suppliers', 'bahanBakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier'  => 'required|max:100|unique:supplier,nama_supplier',
            'no_hp'          => 'nullable|numeric|unique:supplier,no_hp',
            'bahan_baku_ids' => 'required|array', 
        ], [
            'nama_supplier.unique' => 'Gagal! Nama supplier ini sudah terdaftar.',
            'no_hp.unique'         => 'Gagal! Nomor HP ini sudah digunakan supplier lain.',
            'bahan_baku_ids.required' => 'Wajib memilih minimal satu bahan baku.',
        ]);

        $lastSupplier = Supplier::orderBy('id', 'desc')->first();
        $number = $lastSupplier ? ((int) substr($lastSupplier->kode_supplier, 4)) + 1 : 1;
        $kode = 'SPL-' . str_pad($number, 3, '0', STR_PAD_LEFT);

        
        $supplier = Supplier::create([
            'kode_supplier'   => $kode,
            'nama_supplier'   => $request->nama_supplier,
            'alamat_supplier' => $request->alamat_supplier,
            'no_hp'           => $request->no_hp,
        ]);

        
        $supplier->bahanBaku()->attach($request->bahan_baku_ids);

        return redirect()->route('owner.partner.supplier.index')->with('success', 'Supplier berhasil ditambah!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_supplier'  => 'required|max:100|unique:supplier,nama_supplier,' . $id . ',id',
            'no_hp'          => 'nullable|numeric', 
            'bahan_baku_ids' => 'required|array', 
        ], [
            'nama_supplier.unique' => 'Gagal! Nama sudah digunakan supplier lain.',
            'bahan_baku_ids.required' => 'Wajib memilih minimal satu bahan baku.',
        ]);

        $supplier = Supplier::findOrFail($id);

        $supplier->update($request->only(['nama_supplier', 'alamat_supplier', 'no_hp']));

        $supplier->bahanBaku()->sync($request->bahan_baku_ids);

        return redirect()->route('owner.partner.supplier.index')->with('success', 'Data supplier dan bahan baku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return redirect()->back()->with('success', 'Supplier dihapus!');
    }
}
