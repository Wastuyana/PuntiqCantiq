<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Setting;
use App\Models\Produk;
use App\Services\ProductionService;

class RekomendasiProdController extends Controller
{
    protected $productionService;

    public function __construct(ProductionService $productionService)
    {
        $this->productionService = $productionService;
    }
    
    public function index()
    {
        $data = $this->productionService->getRekomendasiProduksi();

        return view('admin.produksi.rekomendasi_prod', $data);
         
    }
}
