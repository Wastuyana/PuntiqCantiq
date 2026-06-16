<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\FasilitasCheck;
use Illuminate\Support\Facades\Auth;

class StandarProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        if ($request->has('settings')) {
            foreach ($request->settings as $key => $value) {
                Setting::where('key', $key)->update([
                    'value' => $value,
                    'updated_at' => now()
                ]);
            }
        }

        // 2. Proses Update Tabel Kelayakan Fasilitas
        if ($request->has('sop_fasilitas')) {
            foreach ($request->sop_fasilitas as $slug => $data) {
                FasilitasCheck::where('slug', $slug)->update([
                    'status' => $data['status'],
                    'tanggal_cek' => now(),
                    'user_id' => Auth::id() // Catat ID user yang mengecek data
                ]);
            }
        }

        return redirect()->back()->with('success', 'Semua pengaturan dan status kelayakan berhasil diperbarui!');
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
        $settings = Setting::all();
        $fasilitas = FasilitasCheck::with('user')->get();

        return view('owner.settings.index', compact('settings', 'fasilitas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Update Tabel Settings (Looping berdasarkan key form)
        if ($request->has('settings')) {
            foreach ($request->settings as $key => $value) {
                Setting::where('key', $key)->update([
                    'value' => $value,
                    'updated_at' => now()
                ]);
            }
        }

        // 2. Update Tabel Kelayakan Fasilitas & Catat Penanggung Jawabnya
        if ($request->has('sop_fasilitas')) {
            foreach ($request->sop_fasilitas as $slug => $data) {
                FasilitasCheck::where('slug', $slug)->update([
                    'status' => $data['status'],
                    'tanggal_cek' => now(),
                    'user_id' => Auth::id()
                ]);
            }
        }

        return redirect()->back()->with('success', 'Semua pengaturan dan status kelayakan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
