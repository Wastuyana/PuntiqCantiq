<div class="bg-white p-6 rounded-lg shadow-sm border border-slate-100 mb-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
            <i class="fa-solid fa-chart-line mr-2 text-primary"></i>Rekap Capaian Produksi
        </h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
            <p class="text-sm font-bold text-slate-400 mb-1">Total Target</p>
            <h4 class="font-black text-slate-600">{{ number_format($totalTarget) }} <span
                    class="text-xs font-normal">Pack</span></h4>
        </div>

        <div class="p-4 bg-primary/5 rounded-xl border border-primary/20">
            <p class="text-sm font-bold text-primary mb-1">Total Aktual</p>
            <h4 class="font-black text-primary">{{ number_format($totalAktual) }} <span
                    class="text-xs font-normal">Bks</span></h4>
        </div>

        <div
            class="p-4 {{ $selisihKumulatif < 0 ? 'bg-error/5 border-error/20' : 'bg-success/5 border-success/20' }} rounded-xl border">
            <p class="text-sm font-bold {{ $selisihKumulatif < 0 ? 'text-error' : 'text-success' }} mb-1">
                Selisih</p>
            <h4 class="font-black {{ $selisihKumulatif < 0 ? 'text-error' : 'text-success' }}">
                {{ $selisihKumulatif > 0 ? '+' : '' }}{{ number_format($selisihKumulatif) }}
                <span class="text-xs font-normal">Bks</span>
            </h4>
        </div>
    </div>

    {{-- Progress Bar Kumulatif --}}
    <div class="mt-8">
        <div class="flex justify-between text-[10px] font-bold uppercase mb-2">
            <span class="text-slate-400">Persentase Keberhasilan Produksi</span>
            <span
                class="text-primary">{{ $totalTarget > 0 ? number_format(($totalAktual / $totalTarget) * 100, 1) : 0 }}%</span>
        </div>
        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-primary"
                style="width: {{ $totalTarget > 0 ? ($totalAktual / $totalTarget) * 100 : 0 }}%"></div>
        </div>
        <p class="text-[9px] text-slate-400 mt-2 italic">*Jika angka selisih negatif, artinya terdapat
            penyusutan atau produk gagal selama proses produksi bulan ini.</p>
    </div>
</div>
