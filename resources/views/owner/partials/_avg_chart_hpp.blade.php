<div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Tren HPP Per Varian</h3>
            <p class="text-[10px] text-slate-400">Rata-rata biaya produksi 6 bulan terakhir</p>
        </div>

        <div class="flex gap-2">
            <!-- Filter Kategori -->
            <select id="filterKategoriHpp" class="select select-bordered select-xs text-[10px] font-bold text-slate-600">
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>

            <!-- Filter Produk (Ukuran) -->
            <select id="filterProdukHpp" class="select select-bordered select-xs text-[10px] font-bold text-slate-600">
                <option value="all">Semua Ukuran</option>
                @foreach ($products->unique('ukuran') as $produk)
                    <option value="{{ $produk->ukuran }}">{{ $produk->ukuran }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="h-75 w-full relative">
        <canvas id="chartTrenHPP"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let hppChart;

    function loadHppChart(kategoriId, produkId = 'all') {
        const url = "{{ route('owner.api.hpp_trend') }}";
        // Tambahkan .then(res => res.json()) agar data bisa dibaca Chart.js
        fetch(`${url}?kategori=${encodeURIComponent(kategoriId)}&produk_id=${produkId}`)
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('chartTrenHPP').getContext('2d');
                if (hppChart) {
                    hppChart.destroy();
                }

                hppChart = new Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10,
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                grid: {
                                    borderDash: [5, 5],
                                    color: '#f1f5f9'
                                },
                                ticks: {
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });

            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const filterKategori = document.getElementById('filterKategoriHpp');
        const filterProduk = document.getElementById('filterProdukHpp');

        // Load pertama kali
        loadHppChart(filterKategori.value, filterProduk.value);

        // 1. Saat kategori berubah (Ini sudah ada di kode kamu)
        filterKategori.addEventListener('change', (e) => {
            const kategoriTerpilih = e.target.value;

            fetch("{{ route('owner.api.produk_by_kategori') }}?kategori=" + encodeURIComponent(
                    kategoriTerpilih))
                .then(res => res.json())
                .then(data => {
                    let options = '<option value="all">Semua Ukuran</option>';

                    // LOGIKA: Ambil ukuran yang unik saja dari data produk
                    const daftarUkuran = [...new Set(data.map(p => p.ukuran))];

                    daftarUkuran.forEach(ukuran => {
                        options += `<option value="${ukuran}">${ukuran}</option>`;
                    });

                    // Ganti isi dropdown ukuran secara otomatis
                    document.getElementById('filterProdukHpp').innerHTML = options;

                    // Update grafik ke 'all' karena kategori baru saja diganti
                    loadHppChart(kategoriTerpilih, 'all');
                });
        });

        document.getElementById('filterProdukHpp').addEventListener('change', (e) => {
            const kategori = document.getElementById('filterKategoriHpp').value;
            const ukuran = e.target.value;
            loadHppChart(kategori, ukuran); // Panggil grafik dengan filter ukuran
        });
    });
</script>
