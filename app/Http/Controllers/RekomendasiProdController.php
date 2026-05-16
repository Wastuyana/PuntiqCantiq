<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BatchController;
use App\Models\Batch;
use App\Services\ProductionService;

class RekomendasiProdController extends Controller
{
 protected $productionService;

    public function __construct(ProductionService $productionService)
    {
        $this->productionService = $productionService;
    }

    public function rekomendasiProduksi()
    {
        $data = $this->productionService->getRekomendasiProduksi();

        $generatedNoBatch = Batch::generateNoBatch();

        return view('owner.produksi.rekomendasi', array_merge($data, [
            'generatedNoBatch' => $generatedNoBatch
        ]));
    }

    public function storeKeBatch(Request $request, BatchController $batchController)
    {
       return $batchController->store($request);
    }
}
