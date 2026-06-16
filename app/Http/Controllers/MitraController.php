<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    public function index()
    {
        $mitra = Mitra::orderBy('id', 'desc')->get();
        return view('owner.partner.mitra', compact('mitra'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mitra' => 'required|unique:mitra,nama_mitra',
            'alamat_mitra' => 'required',
            'no_hp'          => 'required|digits_between:11,13',
        ], [
            'nama_mitra.unique' => 'Nama mitra ini sudah terdaftar, gunakan nama lain!'
        ]);

        $latest = Mitra::latest('id')->first();
        if (!$latest) {
            $kode_otomatis = 'MTR-0001';
        } else {
            $string = preg_replace("/[^0-9]/", "", $latest->kode_mitra);
            $angka_baru = (int)$string + 1;
            $kode_otomatis = 'MTR-' . str_pad($angka_baru, 4, '0', STR_PAD_LEFT);
        }

        Mitra::create([
            'kode_mitra' => $kode_otomatis,
            'nama_mitra' => $request->nama_mitra,
            'alamat_mitra'         => $request->alamat_mitra,
            'no_hp'          => $request->no_hp,
        ]);

        return back()->with('success', 'Mitra baru berhasil ditambahkan dengan kode ' . $kode_otomatis);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mitra' => 'required|unique:mitra,nama_mitra,' . $id,
            'alamat_mitra'         => 'required',
            'no_hp'          => 'required',
        ], [
            'nama_mitra.unique' => 'Nama mitra ini sudah digunakan oleh data lain!'
        ]);

        $mitra = Mitra::findOrFail($id);
        $mitra->update([
            'nama_mitra' => $request->nama_mitra,
            'alamat_mitra'         => $request->alamat_mitra,
            'no_hp'          => $request->no_hp,
        ]);

        return back()->with('success', 'Data Mitra berhasil diperbarui!');
    }
    public function show($id)
    {
        $mitra = Mitra::findOrFail($id);
        
        return $mitra; 
    }
    public function destroy($id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->delete();

        return back()->with('success', 'Mitra berhasil dihapus!');
    }
}