<x-app-layout>
    <div class="p-6 bg-base-100 min-h-screen">
        <div class="text-sm breadcrumbs mb-4 opacity-50">
            <ul>
                <li><a href="{{ route('owner.master.bom') }}">BOM</a></li>
                <li>DAFTAR BOM</li>
            </ul>
        </div>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-base-content">Daftar Bills of Materials</h1>
        </div>

        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead class="bg-base-200 font-bold text-base-content">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Ukuran</th>
                            <th>Total HPP Standar</th>
                            <th>Harga Jual</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produks as $produk)
                            <tr>
                                <td>
                                    <div>{{ $produk->kategori }} - {{ $produk->varian }}</div>
                                </td>
                                <td>{{ $produk->ukuran }}</td>
                                <td>
                                    Rp
                                    {{ number_format($produk->hitungHppStandar(), 0, ',', '.') }}
                                </td>
                                <td>
                                    Rp
                                    {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('bom.edit', $produk->id) }}"
                                        class="btn btn-soft btn-sm btn-primary">
                                        Lihat Komposisi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
