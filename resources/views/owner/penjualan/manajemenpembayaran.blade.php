<x-app-layout>
    <div class="p-6 bg-base-100 rounded-lg shadow" x-data="{ tab: 'hutang' }">
        <h2 class="text-2xl font-bold text-warning mb-6">Manajemen Pembayaran</h2>
        
        <div class="flex p-1 bg-base-200 rounded-lg w-fit mb-6">
            <button @click="tab = 'hutang'" :class="tab === 'hutang' ? 'bg-warning text-white' : ''" class="px-6 py-2 rounded-md font-bold text-sm">Hutang Aktif</button>
            <button @click="tab = 'lunas'" :class="tab === 'lunas' ? 'bg-success text-white' : ''" class="px-6 py-2 rounded-md font-bold text-sm">Riwayat Lunas</button>
        </div>
        <div x-show="tab === 'hutang'">
            <table class="table w-full text-xs">
                <thead><tr class="bg-base-200"><th>Waktu</th><th>Pembeli</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($pembayaran_hutang as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal_penj)->format('d M Y') }}</td>
                        <td class="font-bold">{{ $row->status_customer == 'mitra' ? ($row->mitra->nama_mitra ?? 'Mitra') : ($row->pelanggan->nama ?? 'Pelanggan') }}</td>
                        <td class="text-error font-bold">Rp {{ number_format($row->subtotal_harga, 0, ',', '.') }}</td>
                        <td><span class="badge badge-error badge-outline">HUTANG</span></td>
                        <td>
                            <button onclick="document.getElementById('modal_lunas_{{ $row->id }}').showModal()" 
                                    class="btn btn-xs btn-warning">DETAIL</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 italic">Tidak ada hutang aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div x-show="tab === 'lunas'" x-cloak>
            <table class="table w-full text-xs">
                <thead><tr class="bg-base-200"><th>Waktu</th><th>Pembeli</th><th>Total</th><th>Metode</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($riwayat_lunas as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal_penj)->format('d M Y') }}</td>
                        <td class="font-bold">{{ $row->status_customer == 'mitra' ? ($row->mitra->nama_mitra ?? 'Mitra') : ($row->pelanggan->nama ?? 'Pelanggan') }}</td>
                        <td class="text-success font-bold">Rp {{ number_format($row->subtotal_harga, 0, ',', '.') }}</td>
                        <td class="uppercase">{{ $row->metode_pembayaran }}</td>
                        <td><span class="badge badge-success text-white">LUNAS</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 italic">Belum ada riwayat lunas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
       @foreach($pembayaran_hutang as $row)
    <dialog id="modal_lunas_{{ $row->id }}" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Pelunasan Transaksi</h3>
            <form action="{{ route('admin.penjualan.manajemenpembayaran.lunasi', $row->id) }}" method="POST">
                @csrf
                @method('PUT')
                <p class="mb-4 text-sm">Total Tagihan: <b>Rp {{ number_format($row->subtotal_harga, 0, ',', '.') }}</b></p>
            </form>
            <div class="modal-action">
                <button type="button" class="btn" onclick="document.getElementById('modal_lunas_{{ $row->id }}').close()">
                    Tutup
                </button>
            </div>
        </div>
    </dialog>
    @endforeach
</x-app-layout>