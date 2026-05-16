<?php

use App\Http\Controllers\Admin\BatchController as AdminBatchController;
use App\Http\Controllers\Admin\PenyesuaianStokController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\RekomendasiProdController as AdminRekomendasiProdController;
use App\Http\Controllers\Admin\StandarProdController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PanduanKerjaController;
use App\Http\Controllers\RekomendasiProdController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if ($user->hasRole('owner')) {
        return redirect()->route('owner.dashboard');
    }

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    return redirect('/');
})->middleware(['auth'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/{id}/read', [DashboardController::class, 'markAsRead'])->name('notifications.read');
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');

    Route::get('/owner/api/hpp-trend', [DashboardController::class, 'getHppTrend'])->name('owner.api.hpp_trend');
    Route::get('/owner/api/produk-by-kategori', [DashboardController::class, 'getProdukByKategori'])->name('owner.api.produk_by_kategori');

    Route::resource('owner/master/bom', BomController::class)->names('owner.master.bom');

    Route::resource('owner/master/produk', ProdukController::class)->names('owner.master.produk');

    Route::resource('owner/master/panduan_kerja', PanduanKerjaController::class)
        ->only(['index', 'store', 'destroy'])->names('owner.master.panduan');

    Route::get('owner/master/standar_produksi', [PanduanKerjaController::class, 'standarProduksi'])
        ->name('owner.master.standar_produksi');

    Route::post('/owner/master/produk/{id}/update-stok-minimal', [ProdukController::class, 'updateStokMinimal'])
        ->name('owner.produk.updateStokMinimal');

    Route::resource('owner/produksi/batch', BatchController::class)->names('owner.produksi.batch');

    Route::get('owner/produksi/rekomendasi', [RekomendasiProdController::class, 'rekomendasiProduksi'])
        ->name('owner.produksi.rekomendasi');

    Route::post('owner/produksi/rekomendasi', [RekomendasiProdController::class, 'storeKeBatch'])
        ->name('owner.produksi.rekomendasi.keBatch');;

    Route::get('/owner/bahan-baku', [BahanBakuController::class, 'index'])->name('owner.bahan_baku');

    Route::get('/owner/laporan', [LaporanController::class, 'index'])->name('owner.laporan');

    Route::get('/laporan-produksi/export-excel', [LaporanController::class, 'exportExcel'])->name('owner.laporan.export.excel');
    Route::get('/laporan-produksi/export-pdf', [LaporanController::class, 'exportPdf'])->name('owner.laporan.export.pdf');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('admin/produk/produk', AdminProdukController::class)->names('admin.produk');

    Route::post('/admin/produk/produk/{id}/update-stok-minimal', [AdminProdukController::class, 'updateStokMinimal'])
        ->name('admin.produk.updateStokMinimal');

    Route::get('admin/produk/bom', [AdminProdukController::class, 'indexBom'])->name('admin.produk.bom');

    Route::get('admin/produk/{id}/bom', [AdminProdukController::class, 'detailBom'])
        ->name('admin.produk.detailBom');

    Route::resource('admin/produksi/batch', AdminBatchController::class)->names('admin.produksi.batch');

    Route::get('admin/produksi/penyesuaian/create/{batch_id}', [PenyesuaianStokController::class, 'create'])
        ->name('admin.produksi.penyesuaian.create');

    Route::post('admin/produksi/penyesuaian/store', [PenyesuaianStokController::class, 'store'])
        ->name('admin.produksi.penyesuaian.store');

    Route::get('admin/produksi/rekomendasi_prod', [AdminRekomendasiProdController::class, 'rekomendasiProduksi'])
        ->name('admin.produksi.rekomendasi');

    Route::get('admin/produks/standar_prod', [StandarProdController::class, 'index'])
        ->name('admin.produksi.standar_produksi');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
