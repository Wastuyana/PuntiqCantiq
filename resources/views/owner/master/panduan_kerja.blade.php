<x-app-layout>
    <div class="p-6 bg-base-100 min-h-screen">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-base-content">Dokumentasi Panduan Kerja</h1>
        </div>

        <div class="card bg-base-100 shadow-xl border border-base-300">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50px">No</th>
                        <th>Parameter</th>
                        <th>Standar / Instruksi</th>
                        <th>Keterangan</th>
                        <th style="width: 100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($panduans as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->parameter }}</td>
                            <td>{{ $item->standar }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>
                                <form action="{{ route('owner.master.panduan.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus poin ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    <form action="{{ route('owner.master.panduan.store') }}" method="POST">
                        @csrf
                        <tr class="bg-light">
                            <td>#</td>
                            <td>
                                <input type="text" name="parameter" class="form-control"
                                    placeholder="Contoh: Suhu Goreng" required>
                            </td>
                            <td>
                                <input type="text" name="standar" class="form-control" placeholder="Contoh: 170 C"
                                    required>
                            </td>
                            <td>
                                <input type="text" name="keterangan" class="form-control"
                                    placeholder="Catatan tambahan...">
                            </td>
                            <td>
                                <button type="submit" class="btn btn-success btn-sm">
                                    Simpan
                                </button>
                            </td>
                        </tr>
                    </form>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
