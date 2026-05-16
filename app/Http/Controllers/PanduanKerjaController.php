<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PanduanKerja;
use App\Models\Setting;

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
            'parameter' => 'required',
            'standar' => 'required',
        ]);

        PanduanKerja::create($request->all());

        return redirect()->back()->with('success', 'Parameter SOP berhasil ditambahkan!');
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
        //
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

        return view('owner.master.standar_produksi', compact('settings', 'panduans'));
    }
}
