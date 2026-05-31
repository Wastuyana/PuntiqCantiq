<x-app-layout>
    <div class="p-6 space-y-6">
        <h2 class="text-2xl font-bold italic text-gray-800">Manajemen Data Mitra</h2>

        @if(session('success'))
            <div class="alert alert-success shadow-sm font-semibold text-sm">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error shadow-sm font-semibold text-sm text-white">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card bg-base-100 border shadow-sm">
            <div class="card-body p-6">
                <h3 class="font-bold text-lg mb-4 text-warning">+ Tambah Mitra Baru</h3>
                <form action="{{ route('admin.partner.mitra.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Nama Mitra</span></label>
                            <input type="text" name="nama_mitra" value="{{ old('nama_mitra') }}" placeholder="Masukkan nama mitra..." class="input input-bordered w-full input-sm" required>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">No. HP</span></label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="0812..." class="input input-bordered w-full input-sm" required>
                        </div>
                        <button type="submit" class="btn btn-warning text-white font-bold btn-sm">Simpan Data</button>
                    </div>
                    <div class="form-control mt-4">
                        <label class="label"><span class="label-text font-semibold">Alamat Lengkap</span></label>
                        <input type="text" name="alamat_mitra" value="{{ old('alamat') }}" placeholder="Jl. Raya No..." class="input input-bordered w-full input-sm" required>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto border rounded-xl bg-base-100 shadow-sm">
            <table class="table table-zebra">
                <thead class="bg-base-200 text-gray-700">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Mitra</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mitra as $item)
                    <tr>
                        <td class="font-mono font-bold text-amber-600 text-xs bg-amber-50 rounded-lg px-2 py-1 text-center max-w-[90px] block border border-amber-200 shadow-sm mt-2">{{ $item->kode_mitra }}</td>
                        <td class="font-bold text-gray-800">{{ $item->nama_mitra }}</td>
                        <td>{{ $item->no_hp }}</td>
                        <td class="text-sm opacity-70">{{ \Illuminate\Support\Str::limit($item->alamat_mitra, 40) }}</td>
                        <td class="flex justify-center gap-3">
                            <button onclick="document.getElementById('edit-mitra-{{ $item->id }}').showModal()" class="btn btn-ghost btn-xs text-info uppercase font-bold">Edit</button>
                            
                            <form action="{{ route('admin.partner.mitra.destroy', $item->id) }}" method="POST">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-xs text-error uppercase font-bold" onclick="return confirm('Hapus mitra {{ $item->nama_mitra }}?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>

                    <dialog id="edit-mitra-{{ $item->id }}" class="modal">
                        <div class="modal-box border-t-4 border-warning">
                            <h3 class="font-bold text-lg">Edit Mitra: <span class="text-amber-600">{{ $item->kode_mitra }}</span></h3>
                            
                            <form action="{{ route('admin.partner.mitra.update', $item->id) }}" method="POST" class="space-y-4 mt-4">
                                @csrf 
                                @method('PUT')
                                
                                <div class="form-control">
                                    <label class="label-text font-semibold mb-1">Nama Mitra</label>
                                    <input type="text" name="nama_mitra" value="{{ $item->nama_mitra }}" class="input input-bordered w-full" required>
                                </div>
                                <div class="form-control">
                                    <label class="label-text font-semibold mb-1">No. HP</label>
                                    <input type="text" name="no_hp" value="{{ $item->no_hp }}" class="input input-bordered w-full" required>
                                </div>
                                <div class="form-control">
                                    <label class="label-text font-semibold mb-1">Alamat</label>
                                    <textarea name="alamat_mitra" class="textarea textarea-bordered w-full" required>{{ $item->alamat_mitra }}</textarea>
                                </div>

                                <div class="modal-action">
                                    <button type="button" onclick="this.closest('dialog').close()" class="btn btn-ghost">Batal</button>
                                    <button type="submit" class="btn btn-warning text-white">Update Data</button>
                                </div>
                            </form>
                        </div>
                    </dialog>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 opacity-50 italic">Belum ada data mitra tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>