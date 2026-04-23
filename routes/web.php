<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PanduanKerjaController;
use App\Http\Controllers\PenyesuaianStokController;

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

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', function () {
        return view('owner.dashboard'); // Arahkan ke file blade owner
    })->name('owner.dashboard');

    Route::get('owner/master/bom', [ProdukController::class, 'indexBom'])->name('owner.master.bom');

    Route::resource('owner/master/bom', BomController::class)
        ->only(['store', 'update', 'destroy', 'edit']);

    Route::resource('owner/master/produk', ProdukController::class)->names('owner.master.produk');

    Route::resource('owner/master/panduan_kerja', PanduanKerjaController::class)
        ->only(['index', 'store', 'destroy'])->names('owner.master.panduan');

    Route::get('owner/master/higiene_profile', [PanduanKerjaController::class, 'indexHigiene'])
        ->name('owner.master.higiene_profile');

    Route::post('/owner/master/produk/{id}/update-stok-minimal', [ProdukController::class, 'updateStokMinimal'])
        ->name('owner.produk.updateStokMinimal');
        
    Route::resource('owner/produksi/batch', BatchController::class)->names('owner.produksi.batch');

    Route::get('owner/produksi/penyesuaian/create/{batch_id}', [PenyesuaianStokController::class, 'create'])
        ->name('owner.produksi.penyesuaian.create');

    Route::post('owner/produksi/penyesuaian/store', [PenyesuaianStokController::class, 'store'])
        ->name('owner.produksi.penyesuaian.store');
    Route::get('/owner/bahan-baku', [BahanBakuController::class, 'index'])->name('owner.bahan_baku');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard'); // Arahkan ke file blade admin
    })->name('admin.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
