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
        // Validasi: nama_pelanggan harus unik di tabel pelanggans
        $request->validate([
            'nama_mitra' => 'required|unique:mitra,nama_mitra',
            'alamat_mitra' => 'required',
            'no_hp'          => 'required|digits_between:11,13',
        ], [
            'nama_mitra.unique' => 'Nama mitra ini sudah terdaftar, gunakan nama lain!'
        ]);

        // --- PROSES AUTO GENERATE KODE PELANGGAN (PEL-0001) ---
        $latest = Mitra::latest('id')->first();
        if (!$latest) {
            $kode_otomatis = 'MTR-0001';
        } else {
            // Mengambil angka dari kode terakhir, misal PEL-0001 diambil 1
            $string = preg_replace("/[^0-9]/", "", $latest->kode_mitra);
            $angka_baru = (int)$string + 1;
            // Pad dengan angka 0 di depan agar formatnya tetap 4 digit (0002)
            $kode_otomatis = 'MTR-' . str_pad($angka_baru, 4, '0', STR_PAD_LEFT);
        }

        // Simpan ke database
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
        // Validasi unik untuk update (abaikan nama milik data ini sendiri yang sedang di-edit)
        $request->validate([
            'nama_mitra' => 'required|unique:mitra,nama_pmitra,' . $id,
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

    public function destroy($id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->delete();

        return back()->with('success', 'Mitra berhasil dihapus!');
    }
}