<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Log Kedatangan Bahan</h2>
                <p class="text-sm text-gray-500">Kelola data masuk & tracking harga satuan otomatis</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm mb-6 text-white font-medium">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="card bg-base-100 shadow-sm border border-base-300 mb-8">
            <div class="card-body p-6">
                <h3 class="font-bold text-lg mb-4">Input Kedatangan Baru</h3>
                <form action="{{ route('admin.inventory.bahan_masuk.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Supplier</span></label>
                            <select name="supplier_id" class="select select-bordered w-full" required>
                                <option disabled selected>Pilih...</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Bahan Baku</span></label>
                            <select name="bahan_baku_id" class="select select-bordered w-full" required>
                                <option disabled selected>Pilih...</option>
                                @foreach ($bahanBaku as $bb)
                                    <option value="{{ $bb->id }}">[{{ $bb->kode_bahan }}] {{ $bb->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Tgl Pesan</span></label>
                            <input type="date" name="tanggal_pesan" class="input input-bordered w-full" required>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Tgl Datang</span></label>
                            <input type="date" name="tanggal_masuk" class="input input-bordered w-full" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Jumlah</span></label>
                            <div class="flex">
                                <input type="number" name="jumlah_total" class="input input-bordered w-full rounded-r-none" placeholder="0" required>
                                <span class="bg-base-200 border border-l-0 border-base-300 px-3 flex items-center rounded-r-lg text-xs font-bold text-gray-500">Unit</span>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Total Harga Beli</span></label>
                            <input type="number" name="harga_beli" class="input input-bordered w-full" placeholder="Rp" required>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="btn btn-warning px-10 text-white font-bold">Catat Kedatangan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-base-100 rounded-xl border border-base-300 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead class="bg-base-200">
                        <tr>
                            <th>Tgl Pesan</th>
                            <th>Tgl Datang</th>
                            <th>Lead Time</th>
                            <th>Bahan</th>
                            <th>Supplier</th>
                            <th class="text-right">Jumlah</th>
                            <th class="text-right">Total Bayar</th>
                            <th class="text-right">Status Verifikasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bahanMasuk as $bm)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($bm->tanggal_pesan)->format('d/m/y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($bm->tanggal_masuk)->format('d/m/y') }}</td>
                                <td class="font-bold text-info">
                                    {{ \Carbon\Carbon::parse($bm->tanggal_pesan)->diffInDays(\Carbon\Carbon::parse($bm->tanggal_masuk)) }} Hari
                                </td>
                                <td><span class="font-bold">{{ $bm->bahan_baku->nama }}</span></td>
                                <td>{{ $bm->supplier->nama_supplier }}</td>
                                <td class="text-right">{{ number_format($bm->jumlah_total) }}</td>
                                <td class="text-right font-bold text-primary">Rp {{ number_format($bm->harga_beli) }}</td>
                                <td class="text-right">{{$bm->status}}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.inventory.bahan_masuk.destroy', $bm->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-ghost btn-xs text-error">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-10 italic">Data kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>