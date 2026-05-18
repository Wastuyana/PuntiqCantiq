<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Master Bahan Baku</h2>
                <p class="text-sm text-gray-500">Kelola stok dan harga bahan baku produksi</p>
            </div>
            <label for="modal-tambah-bb" class="btn btn-primary shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Bahan
            </label>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-6 shadow-sm font-medium">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Tabel -->
        <div class="bg-base-100 rounded-xl border border-base-300 overflow-hidden shadow-sm">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Bahan</th>
                        <th>Satuan</th>
                        <th>Harga Satuan</th>
                        <th>Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahanBakus as $bb)
                        <tr>
                            <td><span class="badge badge-ghost font-mono text-xs">{{ $bb->kode_bahan }}</span></td>
                            <td class="font-bold text-gray-700">{{ $bb->nama }}</td>
                            <td>{{ $bb->satuan }}</td>
                            <td>Rp {{ number_format($bb->harga_satuan, 0, ',', '.') }}</td>
                            <td><span class="font-semibold">{{ $bb->stok }}</span></td>
                            <td class="flex justify-center gap-2">
                                <!-- Edit Button -->
                                <label for="modal-edit-{{ $bb->id }}"
                                    class="btn btn-sm btn-square btn-outline btn-info">✎</label>

                                <!-- Delete Button -->
                                <form action="{{ route('owner.inventory.bahan_baku.destroy', $bb->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus bahan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm btn-square btn-outline btn-error">✕</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 opacity-50">Belum ada data bahan baku.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- --- MODAL SECTION --- -->

    <!-- Modal Tambah -->
    <input type="checkbox" id="modal-tambah-bb" class="modal-toggle" style="display: none !important;" />
    <div class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Tambah Bahan Baku Baru</h3>
            <form action="{{ route('owner.inventory.bahan_baku.store') }}" method="POST">
                @csrf
                <div class="form-control mb-3">
                    <label class="label"><span class="label-text font-semibold">Nama Bahan *</span></label>
                    <input type="text" name="nama" class="input input-bordered w-full" required />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control mb-3">
                        <label class="label"><span class="label-text font-semibold">Satuan *</span></label>
                        <select name="satuan" class="select select-bordered" required>
                            <option value="Kg">Kg</option>
                            <option value="Gram">Gram</option>
                            <option value="Liter">Liter</option>
                        </select>
                    </div>
                    <div class="form-control mb-3">
                        <label class="label"><span class="label-text font-semibold">Stok Awal *</span></label>
                        <input type="number" name="stok" class="input input-bordered" required />
                    </div>
                </div>
                <div class="form-control mb-3">
                    <label class="label"><span class="label-text font-semibold">Harga Satuan (Rp) *</span></label>
                    <input type="number" name="harga_satuan" class="input input-bordered" required />
                </div>
                <div class="modal-action">
                    <label for="modal-tambah-bb" class="btn btn-ghost text-gray-500">Batal</label>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    @foreach ($bahanBakus as $bb)
        <input type="checkbox" id="modal-edit-{{ $bb->id }}" class="modal-toggle"
            style="display: none !important;" />

        <div class="modal">
            <div class="modal-box shadow-xl">
                <h3 class="font-bold text-lg mb-4">Edit Bahan: {{ $bb->nama }}</h3>

                <form action="{{ route('owner.inventory.bahan_baku.update', $bb->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="alert alert-error shadow-sm mb-4 py-2">
                            <div class="flex flex-col items-start text-left text-xs font-bold text-white">
                                @foreach ($errors->all() as $error)
                                    <span>- {{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="form-control mb-3 text-left">
                        <label class="label"><span class="label-text font-semibold text-gray-700">Nama
                                Bahan</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $bb->nama) }}"
                            class="input input-bordered w-full" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-left">
                        <div class="form-control mb-3">
                            <label class="label"><span
                                    class="label-text font-semibold text-gray-700">Satuan</span></label>
                            <input type="text" name="satuan" value="{{ old('satuan', $bb->satuan) }}"
                                class="input input-bordered w-full" required />
                        </div>
                        <div class="form-control mb-3">
                            <label class="label"><span
                                    class="label-text font-semibold text-gray-700">Stok</span></label>
                            <input type="number" name="stok" value="{{ old('stok', $bb->stok) }}"
                                class="input input-bordered w-full" required />
                        </div>
                    </div>

                    <div class="form-control mb-4 text-left">
                        <label class="label"><span class="label-text font-semibold text-gray-700">Harga
                                Satuan</span></label>
                        <input type="number" name="harga_satuan"
                            value="{{ old('harga_satuan', $bb->harga_satuan) }}" class="input input-bordered w-full"
                            required />
                    </div>

                    <div class="modal-action">
                        <a href="{{ route('owner.inventory.bahan_baku.index') }}" class="btn btn-ghost text-gray-500">Batal</a>
                        <button type="submit" class="btn btn-primary px-6">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
    <script>
        // Membuka modal otomatis jika ada error validasi
        @if ($errors->any())
            // Default buka modal tambah jika error muncul
            const modalTambah = document.getElementById('modal-tambah');
            if (modalTambah) modalTambah.checked = true;
        @endif
    </script>
</x-app-layout>
