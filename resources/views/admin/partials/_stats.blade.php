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
