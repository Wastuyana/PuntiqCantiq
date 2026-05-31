<x-app-layout>
    <div class="p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Supplier</h2>
                <p class="text-sm text-gray-500">Manajemen data pemasok bahan baku</p>
            </div>

            <label for="modal-tambah" class="btn btn-primary shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Supplier
            </label>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm mb-6 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-base-100 rounded-xl border border-base-300 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead class="bg-base-200">
                        <tr>
                            <th class="text-gray-600">Kode</th>
                            <th class="text-gray-600">Nama Supplier</th>
                            <th class="text-gray-600">Alamat</th>
                            <th class="text-gray-600">Bahan Baku</th>
                            <th class="text-gray-600">No. HP</th>
                            <th class="text-center text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $s)
                            <tr>
                                <td>{{ $s->kode_supplier }}</td>
                                <td>{{ $s->nama_supplier }}</td>
                                <td>{{ $s->alamat_supplier ?? '-' }}</td>
                                <td>{{ $s->nama_bb ?? '-' }}</td>
                                <td>{{ $s->no_hp ?? '-' }}</td>
                                <td class="flex justify-center gap-2">
                                    <label for="modal-edit-{{ $s->id }}"
                                        class="btn btn-sm btn-square btn-outline btn-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </label>

                                    <form action="{{ route('admin.partner.supplier.destroy', $s->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus supplier ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-square btn-outline btn-error">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 opacity-50 italic">Belum ada data supplier.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <input type="checkbox" id="modal-tambah" class="modal-toggle" style="display: none !important;" />
    <div class="modal">
        <div class="modal-box max-w-lg">
            <h3 class="font-bold text-lg mb-4">Tambah Supplier Baru</h3>
            <form action="{{ route('admin.partner.supplier.store') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-error shadow-sm mb-4 py-2">
                        <ul class="text-xs font-bold list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-control w-full mb-3">
                    <label class="label"><span class="label-text font-semibold">Nama Supplier *</span></label>
                    <input type="text" name="nama_supplier" value="{{ old('nama_supplier') }}"
                        class="input input-bordered w-full" required />
                </div>

                <div class="form-control w-full mb-3">
                    <label class="label"><span class="label-text font-semibold">Alamat</span></label>
                    <textarea name="alamat_supplier" class="textarea textarea-bordered h-20">{{ old('alamat_supplier') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control w-full mb-3">
                        <label class="label"><span class="label-text font-semibold">Bahan Baku</span></label>
                        <input type="text" name="nama_bb" value="{{ old('nama_bb') }}"
                            class="input input-bordered w-full" />
                    </div>
                    <div class="form-control w-full mb-3">
                        <label class="label"><span class="label-text font-semibold">No. HP</span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                            class="input input-bordered w-full" />
                    </div>
                </div>

                <div class="modal-action">
                    <a href="{{ route('admin.partner.supplier.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary px-6">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($suppliers as $s)
        <input type="checkbox" id="modal-edit-{{ $s->id }}" class="modal-toggle"
            style="display: none !important;" />
        <div class="modal">
            <div class="modal-box max-w-lg text-left">
                <h3 class="font-bold text-lg mb-4">Edit Supplier: {{ $s->nama_supplier }}</h3>
                <form action="{{ route('admin.partner.supplier.update', $s->id) }}" method="POST">
                    @csrf @method('PUT')
                    @if ($errors->any())
                        <div class="alert alert-error shadow-sm mb-4 py-2">
                            <ul class="text-xs font-bold list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-control w-full mb-3">
                        <label class="label"><span class="label-text font-semibold">Nama Supplier *</span></label>
                        <input type="text" name="nama_supplier"
                            value="{{ old('nama_supplier', $s->nama_supplier) }}" class="input input-bordered w-full"
                            required />
                    </div>

                    <div class="form-control w-full mb-3">
                        <label class="label"><span class="label-text font-semibold">Alamat</span></label>
                        <textarea name="alamat_supplier" class="textarea textarea-bordered h-20">{{ old('alamat_supplier', $s->alamat_supplier) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control w-full mb-3">
                            <label class="label"><span class="label-text font-semibold">Bahan Baku</span></label>
                            <input type="text" name="nama_bb" value="{{ old('nama_bb', $s->nama_bb) }}"
                                class="input input-bordered w-full" />
                        </div>
                        <div class="form-control w-full mb-3">
                            <label class="label"><span class="label-text font-semibold">No. HP</span></label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $s->no_hp) }}"
                                class="input input-bordered w-full" />
                        </div>
                    </div>

                    <div class="modal-action">
                        <a href="{{ route('admin.partner.supplier.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary px-6">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script>
        @if ($errors->any())
            document.getElementById('modal-tambah').checked = true;
        @endif
    </script>
</x-app-layout>
