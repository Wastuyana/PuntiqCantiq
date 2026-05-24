<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index() {
        $produk = Produk::all();
        return view('owner.stok', compact('produk'));
    }
}