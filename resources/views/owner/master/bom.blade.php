<x-app-layout>
    <div class="p-6 bg-base-100 min-h-screen">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-base-content">Daftar Bills of Materials</h1>
            <p class="text-sm text-base-content/60">Kelola standar resep dan komposisi produksi untuk setiap varian.</p>
        </div>

        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead class="bg-base-200">
                        <tr>
                            <th>Produk / Varian</th>
                            <th>Jumlah Bahan</th>
                            <th>Total HPP Standar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produks as $produk)
                            <tr>
                                <td>
                                    <div class="font-bold">{{ $produk->varian }}</div>
                                    <div class="text-xs opacity-50">{{ $produk->kategori }}</div>
                                </td>
                                <td>
                                    <div class="badge badge-ghost">{{ $produk->bom->count() }} Bahan</div>
                                </td>
                                <td class="font-bold text-primary">
                                    Rp
                                    {{ number_format($produk->hitungHppStandar(), 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('bom.edit', $produk->id) }}"
                                        class="btn btn-sm btn-warning">
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
