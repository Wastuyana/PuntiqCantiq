<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Daftar Bahan Baku</h2>
            <button class="btn btn-primary p-2">Tambah</button>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Nama Bahan</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Harga Satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bahanBakus as $item)
                    <tr>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->stok }}</td>
                        <td>{{ $item->satuan }}</td>
                        <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-ghost btn-xs text-info">Edit</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>