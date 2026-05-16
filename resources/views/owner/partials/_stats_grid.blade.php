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
                <span class="text-green-600 font-medium text-[9px]">✅ Biaya lebih hemat dari standar</span>
            @endif
        </div>
    </div>
</div>
