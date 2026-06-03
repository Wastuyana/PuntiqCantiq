<div class="bg-white p-6 rounded-lg shadow border border-base-200 mt-6">
    <h2 class="text-lg font-bold mb-4 text-gray-700">Riwayat Penjualan Terbaru</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b text-sm text-gray-500">
                    <th class="py-2">Order #</th>
                    <th class="py-2">Mitra/Pelanggan</th>
                    <th class="py-2">Total</th>
                    <th class="py-2 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                {{-- Gunakan @forelse agar tetap rapi jika data kosong --}}
                @forelse($riwayatPenjualan as $row)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-3 font-medium text-gray-800">#{{ $row->id }}</td>
                    <td class="py-3 text-gray-600">
                        {{ $row->nama_mitra ?? $row->nama_pelanggan ?? 'Umum' }}
                    </td>
                    <td class="py-3 font-semibold text-gray-800">
                        Rp {{ number_format($row->subtotal_harga ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-[10px] uppercase font-bold 
                            {{ $row->metode_pembayaran != 'hutang' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $row->metode_pembayaran }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-10 text-center text-gray-400">
                        Belum ada riwayat penjualan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>