<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Log Kedatangan Bahan</h2>
                <p class="text-sm text-gray-500">Kelola data masuk sebelum proses Quality Control (QC)</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm mb-6 text-white font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Form Input -->
        <div class="card bg-base-100 shadow-sm border border-base-300 mb-8">
            <div class="card-body p-6">
                <h3 class="font-bold text-lg mb-4">Input Kedatangan Baru</h3>
                <form action="{{ route('owner.inventory.bahan_masuk.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Supplier</span></label>
                            <select name="supplier_id" class="select select-bordered w-full" required>
                                <option disabled selected>Pilih Supplier</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->supplier_id }}">{{ $s->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Bahan Baku</span></label>
                            <select name="id" class="select select-bordered w-full" required>
                                <option disabled selected>Pilih Bahan</option>
                                @foreach ($bahanBakus as $bb)
                                    <option value="{{ $bb->id }}">[{{ $bb->kode_bahan }}] {{ $bb->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Tanggal</span></label>
                            <input type="date" name="tanggal_masuk" class="input input-bordered w-full"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Jumlah</span></label>
                            <input type="number" name="jumlah_total" class="input input-bordered w-full"
                                placeholder="0" required>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Harga Beli</span></label>
                            <input type="number" name="harga_beli" class="input input-bordered w-full" placeholder="Rp"
                                required>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="btn btn-warning px-10 text-white font-bold">Catat
                            Kedatangan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Riwayat -->
        <div class="bg-base-100 rounded-xl border border-base-300 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead class="bg-base-200">
                        <tr>
                            <th>Tanggal</th>
                            <th>Bahan Baku</th>
                            <th>Supplier</th>
                            <th class="text-right">Jumlah</th>
                            <th class="text-right">Total Bayar</th>
                            <th class="text-center">Status QC</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bahanMasuk as $bm)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($bm->tanggal_masuk)->format('d/m/Y') }}</td>
                                <td><span class="font-bold text-gray-700">{{ $bm->bahanBaku->nama }}</span></td>
                                <td>{{ $bm->supplier->nama_supplier }}</td>
                                <td class="text-right font-semibold">{{ number_format($bm->jumlah_total) }}</td>
                                <td class="text-right font-bold text-primary">Rp
                                    {{ number_format($bm->jumlah_total * $bm->harga_beli) }}</td>
                                <td class="text-center">
                                    @if ($bm->status == 'pending')
                                        <div class="badge badge-warning gap-2 py-3 px-4 italic text-xs">
                                            <span class="loading loading-spinner loading-xs"></span>
                                            Menunggu QC
                                        </div>
                                    @else
                                        <div class="badge badge-success gap-2 py-3 px-4 text-white text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Selesai QC
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('owner.inventory.bahan_masuk.destroy', $bm->bm_id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-ghost btn-xs text-error hover:bg-error/10">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 opacity-50 italic">Belum ada riwayat
                                    kedatangan bahan baku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
