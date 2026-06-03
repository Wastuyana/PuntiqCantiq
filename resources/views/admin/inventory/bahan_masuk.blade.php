<x-app-layout>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-6">Pencatatan Kedatangan Bahan</h2>
        
        <div class="card bg-base-100 shadow-sm border mb-8 p-6">
            <h3 class="font-bold text-lg mb-4">Input Barang Datang (Pilih Pesanan)</h3>
            <table class="table w-full">
                <thead><tr><th>Kode PO</th><th>Bahan</th><th>Qty Pesan</th><th>Aksi Pencatatan</th></tr></thead>
                <tbody>
                    @foreach($pesananPending as $p)
                    <tr>
                        <td>{{ $p->kode_pesanan }}</td>
                        <td>{{ $p->bahan_baku->nama }}</td>
                        <td>{{ $p->jumlah_pesan }}</td>
                        <td>
                            <form action="{{ route('admin.inventory.bahan_masuk.update', $p->id) }}" method="POST" class="flex gap-2">
                                @csrf @method('PUT')
                                <input type="date" name="tanggal_masuk" class="input input-bordered w-32" value="{{ date('Y-m-d') }}" required>
                                <input type="number" name="jumlah_total" placeholder="Jumlah Datang" class="input input-bordered w-24" required>
                                <button class="btn btn-warning text-white">Catat</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card bg-base-100 shadow-sm border p-6">
            <h3 class="font-bold text-lg mb-4">Riwayat Barang Masuk</h3>
            <table class="table table-zebra w-full">
                <thead><tr><th>Tgl Masuk</th><th>Bahan</th><th>Qty</th><th>status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($bahanMasuk as $bm)
                    <tr>
                        <td>{{ $bm->tanggal_masuk }}</td>
                        <td>{{ $bm->bahan_baku->nama }}</td>
                        <td>{{ $bm->jumlah_total }}</td>
                        <td>{{ $bm->status}}</td>
                        <td>
                            <form action="{{ route('admin.inventory.bahan_masuk.destroy', $bm->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-ghost btn-xs text-error">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>