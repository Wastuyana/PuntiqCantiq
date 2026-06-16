<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat shadow bg-white rounded-lg">
        <div class="stat-title font-bold uppercase text-[10px]">Efisiensi Hasil Produksi</div>
        <div class="stat-value text-primary text-2xl">
            {{ number_format($efisiensiHasilProd, 1) }}%
        </div>
        <div class="stat-desc text-gray-400">Rata-rata keberhasilan batch</div>
    </div>

    <div class="stat shadow bg-white rounded-lg">
        <div class="stat-title font-bold uppercase text-[10px]">Efisiensi Biaya Produksi</div>
        <div class="stat-value text-2xl">
            @php
                $variance = $efisiensiBiayaProd - 100;
            @endphp

            @if ($efisiensiBiayaProd == 0)
                <span class="text-slate-400">0%</span>
            @elseif($variance > 0)
                <span class="text-error">+{{ number_format($variance, 2) }}%</span>
            @else
                <span class="text-success">{{ number_format($variance, 2) }}%</span>
            @endif
        </div>

        <div class="stat-desc mt-1">
            @if ($efisiensiBiayaProd == 0)
                Tidak ada aktivitas produksi
            @elseif($variance > 0)
                <span class="text-red-500 font-medium text-[9px]">Terjadi pemborosan biaya</span>
            @else
                <span class="text-green-600 font-medium text-[9px]">Biaya lebih hemat dari standar</span>
            @endif
        </div>
    </div>

    <div class="stat shadow bg-white rounded-lg flex flex-col justify-between">
        <div>
            <div class="stat-title font-bold uppercase text-[10px] text-gray-500">Produk Kurang Stok</div>
            <div class="stat-value text-2xl mt-1 flex items-baseline gap-1">
                @if (($jumlahKrisis ?? 0) > 0)
                    <span class="text-error font-black animate-pulse">{{ $jumlahKrisis }}</span>
                    <span class="text-xs font-normal text-gray-400">Produk</span>
                @else
                    <span class="text-success font-bold">0</span>
                    <span class="text-xs font-normal text-gray-400">Produk</span>
                @endif
            </div>
        </div>

        <div class="stat-desc mt-2 pt-1 border-t border-gray-100">
            @if (($jumlahKrisis ?? 0) > 0)
                <a href="{{ route('owner.produksi.rekomendasi.index') }}"
                    class="inline-flex items-center gap-1 text-error hover:text-red-700 font-semibold text-[10px] uppercase tracking-wider transition-colors">
                    Buka Rekomendasi
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="text-green-600 font-medium text-[9px]">Semua stok produk aman</span>
            @endif
        </div>
    </div>
</div>
