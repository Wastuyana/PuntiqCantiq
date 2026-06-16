<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\PanduanKerja;
use App\Models\FasilitasCheck;

class StandarProdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::all();
        $panduans = PanduanKerja::all();
        $fasilitas = FasilitasCheck::with('user')->get();


        return view('admin.produksi.standar_produksi', compact('settings', 'panduans', 'fasilitas'));
    }
}
