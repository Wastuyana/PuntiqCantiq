<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::orderBy('id', 'desc')->get();
        return view('admin.partner.pelanggan', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        // Validasi: nama_pelanggan harus unik di tabel pelanggans
        $request->validate([
            'nama_pelanggan' => 'required|unique:pelanggan,nama_pelanggan',
            'alamat_pelanggan' => 'required',
            'no_hp'          => 'required',
        ], [
            'nama_pelanggan.unique' => 'Nama pelanggan ini sudah terdaftar, gunakan nama lain!'
        ]);

        // --- PROSES AUTO GENERATE KODE PELANGGAN (PEL-0001) ---
        $latest = Pelanggan::latest('id')->first();
        if (!$latest) {
            $kode_otomatis = 'PEL-0001';
        } else {
            // Mengambil angka dari kode terakhir, misal PEL-0001 diambil 1
            $string = preg_replace("/[^0-9]/", "", $latest->kode_pelanggan);
            $angka_baru = (int)$string + 1;
            // Pad dengan angka 0 di depan agar formatnya tetap 4 digit (0002)
            $kode_otomatis = 'PEL-' . str_pad($angka_baru, 4, '0', STR_PAD_LEFT);
        }

        // Simpan ke database
        Pelanggan::create([
            'kode_pelanggan' => $kode_otomatis,
            'nama_pelanggan' => $request->nama_pelanggan,
            'alamat_pelanggan'         => $request->alamat_pelanggan,
            'no_hp'          => $request->no_hp,
        ]);

        return back()->with('success', 'Pelanggan baru berhasil ditambahkan dengan kode ' . $kode_otomatis);
    }

    public function update(Request $request, $id)
    {
        // Validasi unik untuk update (abaikan nama milik data ini sendiri yang sedang di-edit)
        $request->validate([
            'nama_pelanggan' => 'required|unique:pelanggan,nama_pelanggan,' . $id,
            'alamat_pelanggan'         => 'required',
            'no_hp'          => 'required',
        ], [
            'nama_pelanggan.unique' => 'Nama pelanggan ini sudah digunakan oleh data lain!'
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'alamat_pelanggan'         => $request->alamat_pelanggan,
            'no_hp'          => $request->no_hp,
        ]);

        return back()->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return back()->with('success', 'Pelanggan berhasil dihapus!');
    }
}