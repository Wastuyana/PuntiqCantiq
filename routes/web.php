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
use App\Http\Controllers\LaporanProdController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PanduanKerjaController;
use App\Http\Controllers\RekomendasiProdController;
use App\Http\Controllers\BahanMasukController;
use App\Http\Controllers\LaporanPembelianController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\ManajemenPembayaranController;
use App\Http\Controllers\ManajemenPembayaranOwner;
use App\Http\Controllers\ManajemenPembayaranOwnerController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\QcController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\LaporanHppController;
use app\Http\Controllers\Pelanggan;
use App\Http\Controllers\Pelanggan as ControllersPelanggan;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PenjualanMitraController;
use App\Http\Controllers\PenjualanMtrOwnerController;
use App\Http\Controllers\PenjualanPelangganController;
use App\Http\Controllers\PenjualanPlgOwnerController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SupplierOwnerController;
use App\Http\Controllers\StandarProduksiController;
use App\Models\Mitra;

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
        ->names('owner.master.panduan');

    Route::post('/owner/master/produk/{id}/update-stok-minimal', [ProdukController::class, 'updateStokMinimal'])
        ->name('owner.produk.updateStokMinimal');

    Route::resource('owner/produksi/batch', BatchController::class)->names('owner.produksi.batch');

    Route::resource('owner/produksi/rekomendasi', RekomendasiProdController::class)
        ->only(['index', 'store'])->names('owner.produksi.rekomendasi');

    Route::get('owner/produksi/standar_produksi', [PanduanKerjaController::class, 'standarProduksi'])
        ->name('owner.produksi.standar_produksi');

    // Ganti Route::resource kamu dengan ini:
    Route::get('/owner/produksi/standar_prod', [StandarProduksiController::class, 'edit'])->name('owner.produksi.standar_prod.edit');
    Route::put('/owner/produksi/standar_prod', [StandarProduksiController::class, 'update'])->name('owner.produksi.standar_prod.update');
    Route::post('/owner/produksi/standar-produksi', [StandarProduksiController::class, 'store'])->name('owner.produksi.standar_prod.store');

    Route::resource('/owner/inventory/bahan-baku', BahanBakuController::class)->names('owner.inventory.bahan_baku');
    Route::post('/owner/inventory/bahan-baku/{id}/hitung-ulang', [BahanBakuController::class, 'hitungUlang'])
        ->name('owner.inventory.bahan_baku.hitung-ulang');
    Route::resource('/owner/inventory/qc', QcController::class)->names('owner.inventory.qc');
    Route::get('/owner/laporan', [LaporanHppController::class, 'index'])->name('owner.laporan');

    Route::get('/owner/laporan/produksi', [LaporanProdController::class, 'index'])->name('owner.laporan.produksi');
    Route::get('/laporan-produksi/export-excel', [LaporanProdController::class, 'exportExcel'])->name('owner.laporan.export.excel');
    Route::get('/laporan-produksi/export-pdf', [LaporanProdController::class, 'exportPdf'])->name('owner.laporan.export.pdf');

    Route::get('/owner/laporan/hpp', [LaporanHppController::class, 'index'])->name('owner.laporan.hpp');
    Route::get('/laporan-hpp/export-excel', [LaporanHppController::class, 'exportExcel'])->name('owner.laporan.hpp.excel');
    Route::get('/laporan-hpp/export-pdf', [LaporanHppController::class, 'exportPdf'])->name('owner.laporan.hpp.pdf');

    Route::get('/owner/laporan/penjualan', [LaporanPenjualanController::class, 'index'])->name('owner.laporan.penjualan');
    Route::get('/owner/laporan/penjualan/export', [LaporanPenjualanController::class, 'export'])->name('owner.laporan.penjualan.export');
    Route::get('/owner/laporan/pembelian', [LaporanPembelianController::class, 'index'])->name('owner.laporan.pembelian');
    Route::get('owner/laporan/pembelian/export-excel', [LaporanPembelianController::class, 'exportExcel'])->name('owner.laporan.pembelian.export.excel');
    Route::get('owner/laporan/pembelian/export-pdf', [LaporanPembelianController::class, 'exportPdf'])->name('owner.laporan.pembelian.export.pdf');
    Route::resource('/owner/partner/supplier', SupplierOwnerController::class)->names('owner.partner.supplier');
    Route::resource('/owner/partner/mitra', MitraController::class)->names('owner.partner.mitra');
    Route::get('/owner/penjualan/pelanggan', [PenjualanPlgOwnerController::class, 'index'])->name('owner.penjualan.pelanggan.index');
    Route::get('/owner/penjualan/mitra', [PenjualanMtrOwnerController::class, 'index'])->name('owner.penjualan.mitra.index');
    Route::get('/owner/manajemenpembayaran', [ManajemenPembayaranOwnerController::class, 'index'])->name('owner.penjualan.manajemenpembayaran.index');
    Route::get('owner/stok', [StokController::class, 'index'])->name('owner.stok.index');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardAdminController::class, 'index'])->name('admin.dashboard');

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

    Route::get('admin/produksi/rekomendasi_prod', [AdminRekomendasiProdController::class, 'index'])
        ->name('admin.produksi.rekomendasi.index');

    Route::get('admin/produksi/standar_prod', [StandarProdController::class, 'index'])
        ->name('admin.produksi.standar_produksi');
    Route::resource('/admin/penjualan/pelanggan', PenjualanPelangganController::class)->names('admin.penjualan.pelanggan');
    Route::resource('/admin/penjualan/mitra', PenjualanMitraController::class)->names('admin.penjualan.mitra');
    Route::get('/admin/manajemenpembayaran', [ManajemenPembayaranController::class, 'index'])->name('admin.penjualan.manajemenpembayaran.index');
    Route::put('/admin/manajemenpembayaran/{id}/lunasi', [ManajemenPembayaranController::class, 'lunasi'])->name('admin.penjualan.manajemenpembayaran.lunasi');
    Route::resource('/admin/inventory/bahan-masuk', BahanMasukController::class)->names('admin.inventory.bahan_masuk');
    Route::resource('/admin/inventory/pemesanan', PemesananController::class)->names('admin.inventory.pemesanan');
    Route::put('/admin/inventory/{id}/terima', [PemesananController::class, 'terima'])->name('admin.inventory.pemesanan.terima');
    Route::resource('/admin/partner/pelanggan', PelangganController::class)->names('admin.partner.pelanggan');
    Route::post('/pelanggan/store-ajax', [PelangganController::class, 'storeAjax'])->name('admin.pelanggan.store.ajax');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
