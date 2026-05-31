<x-app-layout>
    <div class="p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Pemesanan Bahan Baku (Purchasing)</h2>
            <p class="text-sm text-gray-500">Catat rencana pembelian ke supplier sebelum barang datang</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm mb-6 text-white font-medium">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="card bg-base-100 shadow-sm border border-base-300 mb-8">
            <div class="card-body p-6">
                <h3 class="font-bold text-lg mb-4">Input Pesanan Baru</h3>
                <form action="{{ route('admin.inventory.pemesanan.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Bahan Baku</span></label>
                            <select name="bahan_baku_id" class="select select-bordered w-full" required>
                                <option disabled selected>Pilih Bahan...</option>
                                @foreach($bahan as $b) <option value="{{ $b->id }}">{{ $b->nama }}</option> @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Supplier</span></label>
                            <select name="supplier_id" class="select select-bordered w-full" required>
                                <option disabled selected>Pilih Supplier...</option>
                                @foreach($suppliers as $s) <option value="{{ $s->id }}">{{ $s->nama_supplier }}</option> @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Jumlah Pesan</span></label>
                            <input type="number" name="jumlah_pesan" class="input input-bordered w-full" placeholder="0" required />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Harga Pesan (Rp)</span></label>
                            <input type="number" name="harga_beli" class="input input-bordered w-full" placeholder="Contoh: 10000" required />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Tgl Pesan</span></label>
                            <input type="date" name="tanggal_pesan" class="input input-bordered w-full" value="{{ date('Y-m-d') }}" required />
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="btn btn-primary px-10 font-bold">Catat Pesanan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-base-100 rounded-xl border border-base-300 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead class="bg-base-200">
                        <tr>
                            <th>Kode</th>
                            <th>Tgl Pesan</th>
                            <th>Bahan</th>
                            <th>Supplier</th>
                            <th>Jumlah Pesan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayatPemesanan as $item)
                        <tr>
                            <td class="font-mono font-bold">{{ $item->kode_pesanan }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pesan)->format('d/m/y') }}</td>
                            <td><span class="font-bold">{{ $item->bahan_baku->nama }}</span></td>
                            <td>{{ $item->supplier->nama_supplier }}</td>
                            <td>{{ $item->jumlah_pesan }}</td>
                            <td>
                                @if($item->proses_pemesanan == 'di_pesan')
                                    <span class="badge badge-warning text-white">Menunggu</span>
                                @else
                                    <span class="badge badge-success text-white">Selesai</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->proses_pemesanan == 'di_pesan')
                                    <a href="{{ route('admin.inventory.bahan_masuk.index') }}" class="btn btn-xs btn-info text-white">Input Barang Datang</a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Telah Diproses</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>