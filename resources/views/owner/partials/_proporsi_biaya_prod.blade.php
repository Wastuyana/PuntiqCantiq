<div class="bg-white p-6 rounded-lg shadow-sm border border-slate-100 mb-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
            <i class="fa-solid fa-chart-pie mr-2 text-primary"></i>Proporsi Biaya Produksi (Bulan Ini)
        </h3>
    </div>

    <div class="flex flex-col md:flex-row items-center justify-around gap-8">
        <div class="relative w-40 h-40 shrink-0">
            <canvas id="chartProporsiBiaya"></canvas>
        </div>

        <div class="w-full max-w-xs space-y-3">

            <div class="flex items-center gap-4 p-3 rounded-xl bg-slate-50 border border-slate-100">
                <div class="w-2 h-8 rounded-full bg-[#EAB308]"></div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">Bahan Baku</p>
                    <p class="text-xs font-black text-slate-700">Rp
                        {{ number_format($proporsiBiaya->bahan ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 p-3 rounded-xl bg-slate-50 border border-slate-100">
                <div class="w-2 h-8 rounded-full bg-[#3B82F6]"></div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">Tenaga Kerja</p>
                    <p class="text-xs font-black text-slate-700">Rp
                        {{ number_format($proporsiBiaya->tenaga ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 p-3 rounded-xl bg-slate-50 border border-slate-100">
                <div class="w-2 h-8 rounded-full bg-[#94A3B8]"></div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">Overhead & Lainnya</p>
                    <p class="text-xs font-black text-slate-700">Rp
                        {{ number_format($proporsiBiaya->overhead ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="lex justify-between text-[10px] font-bold uppercase mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">Total Biaya Produksi</p>
                 <p class="text-xs font-black text-slate-700">Rp
                    {{ number_format($proporsiBiaya->total ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('chartProporsiBiaya').getContext('2d');

        const dataBiaya = {
            bahan: {{ $proporsiBiaya->bahan ?? 0 }},
            tenaga: {{ $proporsiBiaya->tenaga ?? 0 }},
            overhead: {{ $proporsiBiaya->overhead ?? 0 }}
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Bahan Baku', 'Tenaga Kerja', 'Overhead'],
                datasets: [{
                    data: [dataBiaya.bahan, dataBiaya.tenaga, dataBiaya.overhead],
                    backgroundColor: [
                        '#EAB308', // Yellow (Banana)
                        '#3B82F6', // Blue
                        '#94A3B8' // Slate/Gray
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', // Bikin lubang tengah lebih besar biar estetik
                plugins: {
                    legend: {
                        display: false // Kita pakai legenda custom HTML di samping agar lebih rapi
                    }
                }
            }
        });
    });
</script>
