<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;

class PenjualanMtrOwnerController extends Controller
{
     public function index()
    {
        $mitra = Mitra::orderBy('nama_mitra', 'asc')->get();
        $produk = Produk::where('stok', '>', 0)->get(); 
        
        // Tambahan: .produk artinya kita sekalian mengambil data produk di dalam detail_penjualan
        $history = Penjualan::with(['mitra', 'Detail_Penjualan.produk'])
                    ->where('status_customer', 'mitra')
                    ->orderBy('tanggal_penj', 'desc')
                    ->get();

        return view('owner.penjualan.mitra', compact('mitra', 'produk', 'history'));
    }
}
