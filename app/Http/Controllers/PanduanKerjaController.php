<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PanduanKerja;
use App\Models\Setting;
use App\Models\FasilitasCheck;

class PanduanKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $panduans = PanduanKerja::all();
        return view('owner.master.panduan_kerja', compact('panduans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parameter' => 'required',
            'standar' => 'required',
        ]);

        PanduanKerja::create($request->all());

        return redirect()->back()->with('success', 'Parameter SOP berhasil ditambahkan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        PanduanKerja::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Parameter SOP berhasil dihapus!');
    }

    public function standarProduksi()
    {
        $settings = Setting::all();
        $panduans = PanduanKerja::all();
        // Ambil data fasilitas beserta user yang nge-cek (eager loading)
        $fasilitas = FasilitasCheck::with('user')->get();

        // Satukan semua variabel ke dalam compact()
        return view('owner.produksi.standar_produksi', compact('settings', 'panduans', 'fasilitas'));
    }
}
