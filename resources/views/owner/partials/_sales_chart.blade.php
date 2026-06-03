<div class="bg-white p-6 shadow-sm sm:rounded-lg border border-base-200 mt-6">
    <h3 class="font-bold text-lg mb-4 text-gray-700">
        Grafik Penjualan per Varian (Bulan {{ $bulan }})
    </h3>
    <div style="height: 300px;">
        <canvas id="salesChart"></canvas>
    </div>
</div>

<script>
    (function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];

        if (window.mySalesChart instanceof Chart) {
            window.mySalesChart.destroy();
        }

        window.mySalesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels ?? []) !!},
                datasets: [{
                    label: 'Total Terjual',
                    data: {!! json_encode($dataSales ?? []) !!},
                    backgroundColor: colors, 
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false } 
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { ticks: { maxRotation: 0, minRotation: 0 } }
                }
            } 
        }); 
    })();
</script>