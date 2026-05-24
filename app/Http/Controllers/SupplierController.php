<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('owner.partner.supplier', compact('suppliers'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Unik (Tambah Baru)
        $request->validate([
            'nama_supplier' => 'required|max:100|unique:Supplier,nama_supplier',
            'no_hp' => 'nullable|numeric|unique:Supplier,no_hp',
        ], [
            'nama_supplier.unique' => 'Gagal! Nama supplier ini sudah terdaftar.',
            'no_hp.unique' => 'Gagal! Nomor HP ini sudah digunakan supplier lain.',
        ]);

        // 2. Buat Kode Unik SPL-XXX
        $lastSupplier = Supplier::orderBy('id', 'desc')->first();
        $number = $lastSupplier ? ((int) substr($lastSupplier->kode_supplier, 4)) + 1 : 1;
        $kode = 'SPL-' . str_pad($number, 3, '0', STR_PAD_LEFT);

        // 3. Simpan
        Supplier::create([
            'kode_supplier' => $kode,
            'nama_supplier' => $request->nama_supplier,
            'alamat_supplier' => $request->alamat_supplier,
            'nama_bb' => $request->nama_bb,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('owner.partner.supplier.index')->with('success', 'Supplier berhasil ditambah!');
    }

    public function update(Request $request, int $id)
    {
        // 1. Validasi Unik (Kecuali ID sendiri)
        $request->validate([
            'nama_supplier' => 'required|max:100|unique:supplier,nama_supplier,' . $id . ',id',
            'no_hp' => 'nullable|numeric|unique:supplier,no_hp,' . $id . ',id',
        ], [
            'nama_supplier.unique' => 'Gagal! Nama sudah digunakan supplier lain.',
            'no_hp.unique' => 'Gagal! Nomor HP sudah digunakan supplier lain.',
        ]);

        // 2. Update Data
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());

        return redirect()->route('owner.supplier.index')->with('success', 'Data diperbarui!');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return redirect()->back()->with('success', 'Supplier dihapus!');
    }
}
