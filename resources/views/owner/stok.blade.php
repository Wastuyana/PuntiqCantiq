<x-app-layout>
    <div class="p-6 bg-base-100 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6 text-primary">Manajemen Stok</h2>
        
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th>Produk</th>
                        <th>Varian</th>
                        <th>Ukuran</th>
                        <th>Gudang</th>
                        <th>Mitra</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produk as $p)
                    <tr>
                        <td class="font-bold">{{ $p->kategori }}</td>
                        <td class="font-bold">{{ $p->varian }}</td>
                        <td class="font-bold">{{ $p->ukuran }}</td>
                        <td class="font-bold">{{ $p->stok }}</td>
                        <td class="font-bold">{{ $p->stok_mitra }}</td>
                        <td class="font-bold">{{ $p->stok + $p->stok_mitra }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>