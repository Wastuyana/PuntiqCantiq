<div class="bg-white p-6 rounded-lg shadow-sm border border-slate-100 mb-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
            <i class="fa-solid fa-chart-pie mr-2 text-primary"></i>Proporsi Biaya Produksi
        </h3>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-around gap-6">

        <div class="relative w-32 h-32 shrink-0">
            <canvas id="chartProporsiBiaya"></canvas>
        </div>

        <div class="w-full max-w-xs space-y-2">

            <div class="flex items-center gap-3 p-2 rounded-lg bg-slate-50 border border-slate-100">
                <div class="w-1.5 h-6 rounded-full bg-[#EAB308]"></div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase leading-none mb-1">Bahan Baku</p>
                    <p class="text-xs font-black text-slate-700">Rp
                        {{ number_format($proporsiBiaya->bahan ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-2 rounded-lg bg-slate-50 border border-slate-100">
                <div class="w-1.5 h-6 rounded-full bg-[#3B82F6]"></div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase leading-none mb-1">Tenaga Kerja</p>
                    <p class="text-xs font-black text-slate-700">Rp
                        {{ number_format($proporsiBiaya->tenaga ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-2 rounded-lg bg-slate-50 border border-slate-100">
                <div class="w-1.5 h-6 rounded-full bg-[#94A3B8]"></div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase leading-none mb-1">Overhead & Lainnya</p>
                    <p class="text-xs font-black text-slate-700">Rp
                        {{ number_format($proporsiBiaya->overhead ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex justify-between items-center pt-2.5 border-t border-slate-100 mt-2 px-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Total Biaya</p>
                <p class="text-xs font-black text-slate-800">Rp
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
                        '#EAB308',
                        '#3B82F6',
                        '#94A3B8'
                    ],
                    borderWidth: 0,
                    hoverOffset: 6 // Disesuaikan agar efek hover pop-outnya proporsional dengan chart kecil
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
