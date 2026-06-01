<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Laporan Penjualan</h2>
                <p class="text-sm text-gray-500">Rekap transaksi dan analisis produk terlaris</p>
            </div>
        </div>

        <div class="tabs tabs-boxed mb-6 bg-base-200 p-1 w-fit">
            <a id="tab-transaksi" class="tab tab-active font-bold" onclick="changeTab('transaksi')">Riwayat Transaksi</a>
            <a id="tab-terlaris" class="tab font-bold" onclick="changeTab('terlaris')">Produk Terlaris</a>
        </div>

        <div class="bg-base-100 p-4 rounded-xl border border-base-300 shadow-sm mb-6">
            <form action="{{ route('owner.laporan.penjualan') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <input type="hidden" name="tab" id="active-tab" value="{{ request('tab', 'transaksi') }}">
                
                <div class="flex flex-col">
                    <label class="label-text font-bold mb-1">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ $dari }}" class="input input-bordered input-sm">
                </div>
                <div class="flex flex-col">
                    <label class="label-text font-bold mb-1">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ $sampai }}" class="input input-bordered input-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <div id="export-buttons">
                        <a href="{{ route('owner.laporan.penjualan.export', ['dari' => $dari, 'sampai' => $sampai, 'format' => 'excel']) }}" 
                           class="btn btn-sm btn-success text-white">Excel</a>
                        <a href="{{ route('owner.laporan.penjualan.export', ['dari' => $dari, 'sampai' => $sampai, 'format' => 'pdf']) }}" 
                           class="btn btn-sm btn-error text-white">PDF</a>
                    </div>
                </div>
            </form>
        </div>

        <div id="content-transaksi" class="content-section bg-base-100 rounded-xl border border-base-300 overflow-hidden shadow-sm">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200">
                    <tr><th>Tanggal</th><th>Produk</th><th>Jumlah</th><th>Total</th></tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->penjualan->tanggal_penj)->format('d M Y') }}</td>
                            <td>
                                <div class="font-bold">{{ $item->produk->kategori ?? 'Dihapus' }}</div>
                                <div class="text-xs text-gray-500">{{ $item->produk->varian ?? '-' }}</div>
                            </td>
                            <td>{{ $item->jumlah_produk }}</td>
                            <td class="font-bold text-primary">
                                Rp {{ number_format($item->jumlah_produk * ($item->produk->harga_jual ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-10 opacity-50">Tidak ada data transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="content-terlaris" class="content-section hidden bg-base-100 rounded-xl border border-base-300 overflow-hidden shadow-sm">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200">
                    <tr><th>Ranking</th><th>Produk</th><th>Total Terjual</th><th>Total Omzet</th></tr>
                </thead>
                <tbody>
                    @foreach($produkTerlaris as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="font-bold">{{ $item['nama'] }}</td>
                            <td><span class="badge badge-warning font-bold">{{ $item['qty'] }} pcs</span></td>
                            <td class="font-bold">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function changeTab(tab) {
            // Update Tab Active
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('tab-active'));
            document.getElementById('tab-' + tab).classList.add('tab-active');
            
            // Toggle Content
            document.querySelectorAll('.content-section').forEach(c => c.classList.add('hidden'));
            document.getElementById('content-' + tab).classList.remove('hidden');
            
            // Update Hidden Input untuk Filter
            document.getElementById('active-tab').value = tab;

            // Sembunyikan tombol export jika di tab produk terlaris
            document.getElementById('export-buttons').style.display = (tab === 'terlaris') ? 'none' : 'block';
        }

        // Jalankan saat load halaman
        const currentTab = "{{ request('tab', 'transaksi') }}";
        changeTab(currentTab);
    </script>
</x-app-layout>