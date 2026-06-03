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
        $request->validate([
            'nama_pelanggan' => 'required|unique:pelanggan,nama_pelanggan',
            'alamat_pelanggan' => 'required',
            'no_hp'          => 'required',
        ], [
            'nama_pelanggan.unique' => 'Nama pelanggan ini sudah terdaftar, gunakan nama lain!'
        ]);

        $latest = Pelanggan::latest('id')->first();
        if (!$latest) {
            $kode_otomatis = 'PEL-0001';
        } else {
            $string = preg_replace("/[^0-9]/", "", $latest->kode_pelanggan);
            $angka_baru = (int)$string + 1;
            $kode_otomatis = 'PEL-' . str_pad($angka_baru, 4, '0', STR_PAD_LEFT);
        }

        Pelanggan::create([
            'kode_pelanggan' => $kode_otomatis,
            'nama_pelanggan' => $request->nama_pelanggan,
            'alamat_pelanggan'         => $request->alamat_pelanggan,
            'no_hp'          => $request->no_hp,
        ]);

        return back()->with('success', 'Pelanggan baru berhasil ditambahkan dengan kode ' . $kode_otomatis);
    }
    public function storeAjax(Request $request)
    {
        // Tambahkan 'unique:pelanggan,nama_pelanggan' di sini
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255|unique:pelanggan,nama_pelanggan',
            'no_hp'          => 'nullable|string|max:20',
        ], [
            // Pesan khusus jika nama sudah ada
            'nama_pelanggan.unique' => 'Nama pelanggan "' . $request->nama_pelanggan . '" sudah terdaftar di database!',
        ]);

        // LOGIKA GENERATE KODE PELANGGAN (sama seperti sebelumnya)
        $latest = \App\Models\Pelanggan::latest('id')->first();
        $kode_otomatis = !$latest ? 'PEL-0001' : 'PEL-' . str_pad((int)preg_replace("/[^0-9]/", "", $latest->kode_pelanggan) + 1, 4, '0', STR_PAD_LEFT);

        // SIMPAN KE DATABASE
        \App\Models\Pelanggan::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp'          => $request->no_hp,
            'kode_pelanggan' => $kode_otomatis,
        ]);

        return back()->with('success', 'Pelanggan berhasil ditambahkan dengan kode: ' . $kode_otomatis);
    }
    public function update(Request $request, $id)
    {
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