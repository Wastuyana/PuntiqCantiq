<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Master Bahan Baku</h2>
                <p class="text-sm text-gray-500">Kelola stok, harga, dan titik pemesanan ulang (ROP)</p>
            </div>
            <button onclick="modal_tambah.showModal()" class="btn btn-primary shadow-md">Tambah Bahan</button>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-6 shadow-sm text-white">{{ session('success') }}</div>
        @endif

        <div class="bg-base-100 rounded-xl border border-base-300 overflow-hidden shadow-sm">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Bahan</th>
                        <th>Harga Satuan</th>
                        <th>Stok</th>
                        <th>Status</th> 
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahanBakus as $bb)
                        <tr>
                            <td><span class="badge badge-ghost font-mono text-xs">{{ $bb->kode_bahan }}</span></td>
                            <td class="font-bold text-gray-700">{{ $bb->nama }}</td>
                            <td>
                                <div>Rp {{ number_format($bb->harga_satuan, 0, ',', '.') }}</div>
                                <div class="flex items-center gap-1.5 opacity-60 text-xs mt-1">
                                    {{ $bb->harga_updated_at ? $bb->harga_updated_at->diffForHumans() : '-' }}
                                </div>
                            </td>
                            <td>{{ $bb->stok }} {{ $bb->satuan }}</td>
                            <td>
                                @if ($bb->stok <= $bb->ss_bahan)
                                    <span class="badge badge-error badge-sm badge-outline">Kritis</span>
                                @elseif ($bb->stok <= $bb->rop_bahan)
                                    <span class="badge badge-warning badge-sm badge-outline">Butuh Restock</span>
                                @else
                                    <span class="badge badge-success badge-sm badge-outline">Aman</span>
                                @endif
                                <div class="flex items-center gap-1.5 opacity-60 text-xs mt-1">
                                    Min: {{ $bb->rop_bahan }}
                                </div>
                            </td>
                            <td class="flex justify-center gap-2">
                                <form action="{{ route('owner.inventory.bahan_baku.hitung-ulang', $bb->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-square btn-outline btn-warning" title="Hitung Ulang SS/ROP">↻</button>
                                </form>
                                
                                <button type="button" onclick="edit_modal_{{ $bb->id }}.showModal()" class="btn btn-sm btn-square btn-outline btn-info">✎</button>
                                
                                <form action="{{ route('owner.inventory.bahan_baku.destroy', $bb->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-square btn-outline btn-error">✕</button>
                                </form>
                            </td>
                        </tr>

                        <dialog id="edit_modal_{{ $bb->id }}" class="modal">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg mb-4">Edit Bahan: {{ $bb->nama }}</h3>
                                <form action="{{ route('owner.inventory.bahan_baku.update', $bb->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="form-control mb-3">
                                        <label class="label"><span class="label-text">Nama Bahan</span></label>
                                        <input type="text" name="nama" value="{{ $bb->nama }}" class="input input-bordered w-full" required />
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="form-control mb-3">
                                            <label class="label"><span class="label-text">Stok</span></label>
                                            <input type="number" name="stok" value="{{ $bb->stok }}" class="input input-bordered w-full" required />
                                        </div>
                                        <div class="form-control mb-3">
                                            <label class="label"><span class="label-text">Satuan (kg/pcs)</span></label>
                                            <input type="text" name="satuan" value="{{ $bb->satuan }}" class="input input-bordered w-full" required />
                                        </div>
                                    </div>
                                    <div class="form-control mb-3">
                                        <label class="label"><span class="label-text">Harga Satuan</span></label>
                                        <input type="number" name="harga_satuan" value="{{ $bb->harga_satuan }}" class="input input-bordered w-full" required />
                                    </div>
                                    <div class="modal-action">
                                        <button type="button" class="btn btn-ghost" onclick="edit_modal_{{ $bb->id }}.close()">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </dialog>
                    @empty
                        <tr><td colspan="6" class="text-center py-10 opacity-50">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <dialog id="modal_tambah" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Tambah Bahan Baru</h3>
            <form action="{{ route('owner.inventory.bahan_baku.store') }}" method="POST">
                @csrf
                <div class="form-control mb-3">
                    <label class="label"><span class="label-text">Nama Bahan</span></label>
                    <input type="text" name="nama" class="input input-bordered w-full" required />
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div class="form-control mb-3">
                        <label class="label"><span class="label-text">Satuan</span></label>
                        <input type="text" name="satuan" placeholder="kg/pcs" class="input input-bordered w-full" required />
                    </div>
                    <div class="form-control mb-3">
                        <label class="label"><span class="label-text">Stok</span></label>
                        <input type="number" name="stok" class="input input-bordered w-full" required />
                    </div>
                    <div class="form-control mb-3">
                        <label class="label"><span class="label-text">Harga</span></label>
                        <input type="number" name="harga_satuan" class="input input-bordered w-full" required />
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="modal_tambah.close()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Bahan</button>
                </div>
            </form>
        </div>
    </dialog>
</x-app-layout>