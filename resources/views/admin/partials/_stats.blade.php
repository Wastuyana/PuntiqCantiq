<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <!-- Batch Aktif -->
    <div class="stat shadow bg-white rounded-lg">
        <div class="stat-title font-bold uppercase text-[10px]">Batch Aktif</div>
        <div class="stat-value text-primary-content
         text-2xl">
            2
        </div>
        <div class="stat-desc text-gray-400">Btach dalam proses</div>
    </div>

    <div class="stat bg-white shadow rounded-lg">
        <div class="stat-title font-bold uppercase text-[10px]">Stok Produk Jadi</div>
        <div class="stat-value text-2xl text-info">
            1000<span class="text-xs font-normal">Pcs</span>
        </div>
        <div class="stat-desc text-gray-400">Tersedia di gudang</div>
    </div>

    <div class="stat bg-white shadow rounded-lg border-l-4 border-l-error">
        <div class="stat-value text-2xl text-error">
            3<span class="text-xs font-normal">Varian Produk</span>
        </div>
        <div class="stat-desc text-red-400 font-bold">Butuh Produksi!</div>
        <a href="/rekomendasi-produksi" class="text-error text-xs
            ">
            Lihat Rekomendasi
        </a>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow border border-base-200">
        <h3 class="text-gray-500 text-sm font-medium">Transaksi Lunas</h3>
        <p class="text-3xl font-bold text-green-600">{{ $stats->lunas_count ?? 0 }}</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border border-base-200">
        <h3 class="text-gray-500 text-sm font-medium">Transaksi Hutang</h3>
        <p class="text-3xl font-bold text-red-600">{{ $stats->hutang_count ?? 0 }}</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border border-base-200">
        <h3 class="text-gray-500 text-sm font-medium">Total Omzet</h3>
        <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($stats->total_omzet ?? 0, 0, ',', '.') }}</p>
    </div>
</div>
