<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Laporan Penjualan</h2>
                <p class="text-sm text-gray-500">Rekap transaksi penjualan produk</p>
            </div>
        </div>

        <div class="bg-base-100 p-4 rounded-xl border border-base-300 shadow-sm mb-6">
            <form action="{{ route('owner.laporan.penjualan') }}" method="GET" class="flex flex-wrap gap-4 items-end">
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
                    <a href="{{ route('owner.laporan.penjualan.export', ['dari' => $dari, 'sampai' => $sampai, 'format' => 'excel']) }}" 
                       class="btn btn-sm btn-success text-white">Excel</a>
                    <a href="{{ route('owner.laporan.penjualan.export', ['dari' => $dari, 'sampai' => $sampai, 'format' => 'pdf']) }}" 
                       class="btn btn-sm btn-error text-white">PDF</a>
                </div>
            </form>
        </div>

        <div class="bg-base-100 rounded-xl border border-base-300 overflow-hidden shadow-sm">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200">
                    <tr>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->penjualan->tanggal_penj)->format('d M Y') }}</td>
                            
                            <td>
                                <div class="font-bold">{{ $item->produk->kategori ?? 'Produk Dihapus' }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $item->produk->varian }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $item->produk->ukuran ?? 'Tanpa Ukuran' }}
                                </div>
                            </td>
                            
                            <td>{{ $item->jumlah_produk }}</td>
                            
                            <td>Rp {{ number_format($item->produk->harga_jual, 0, ',', '.') }}</td>
                            
                            <td class="font-bold text-primary">
                                Rp {{ number_format($item->jumlah_produk * $item->produk->harga_jual, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 opacity-50">Tidak ada data transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>