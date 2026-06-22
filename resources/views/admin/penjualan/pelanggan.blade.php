<x-app-layout>
    <div class="p-6 space-y-8">
        <div>
            <h2 class="text-2xl font-bold italic text-gray-800">Manajemen Penjualan Pelanggan</h2>
            <p class="text-xs text-gray-500">Input transaksi baru dan pantau riwayat penjualan langsung di satu tempat</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm font-semibold text-sm">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-error text-white font-semibold text-sm shadow-sm">
                <ul>@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.penjualan.pelanggan.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf

            <div class="card bg-base-100 border shadow-sm p-5 space-y-4 h-fit">
                <h3 class="font-bold text-base border-b pb-2 text-warning">Detail Kasir</h3>
                
                <div class="form-control">
                    <label class="label-text font-semibold mb-1 text-xs">Nama Pelanggan</label>
                    <div class="flex gap-2">
                        <select name="pelanggan_id" class="select select-bordered select-sm w-full" required>
                            <option value="" disabled selected>-- Pilih Pelanggan --</option>
                            @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_pelanggan }} ({{ $p->kode_pelanggan }})
                                </option>
                            @endforeach
                        </select>
                        <button type="button" onclick="modal_tambah_pelanggan.showModal()" class="btn btn-sm btn-square btn-outline">+</button>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label-text font-semibold mb-1 text-xs">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="select select-bordered select-sm w-full" required>
                        <option value="" disabled selected>-- Pilih Pembayaran --</option>
                        <option value="cash">Cash / Tunai</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-warning btn-block text-white font-bold btn-sm shadow-sm">Simpan Transaksi</button>
                </div>
            </div>

            <div class="card bg-base-100 border shadow-sm p-5 lg:col-span-2 space-y-4">
                <h3 class="font-bold text-base border-b pb-2 text-neutral">Pilih Varian Produk</h3>
                <div class="overflow-x-auto max-h-60">
                    <table class="table table-compact w-full">
                        <thead class="sticky top-0 bg-base-100 z-10">
                            <tr class="bg-base-200 text-xs">
                                <th>Pilih</th><th>Item</th><th>Harga</th><th>Stok</th><th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produk as $item)
                            <tr>
                                <td><input type="checkbox" name="produk_id[]" value="{{ $item->id }}" class="checkbox checkbox-sm checkbox-warning" onchange="toggleQty(this)"></td>
                                <td class="font-bold text-sm">{{ $item->kategori }} - {{ $item->varian }}</td>
                                <td class="text-amber-600 font-semibold text-xs">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-xs">{{ $item->stok }}</td>
                                <td><input type="number" name="jumlah_produk[]" min="1" max="{{ $item->stok }}" class="input input-bordered input-sm w-20 qty-field" disabled></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <div class="space-y-3">
            <h3 class="font-bold text-lg">Riwayat Transaksi</h3>
            <form method="GET" action="{{ route('admin.penjualan.pelanggan.index') }}" class="flex flex-wrap gap-4 items-end mb-4 bg-base-200 p-4 rounded-xl">
                <div class="form-control">
                    <label class="label-text font-bold">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="input input-sm input-bordered">
                </div>
                <div class="form-control">
                    <label class="label-text font-bold">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="input input-sm input-bordered">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-sm btn-warning text-white">Filter</button>
                    <a href="{{ route('admin.penjualan.pelanggan.index') }}" class="btn btn-sm btn-ghost">Reset</a>
                </div>
            </form>
            <div class="overflow-x-auto border rounded-xl bg-base-100">
                <table class="table table-zebra">
                    <thead class="bg-base-200 text-gray-700">
                        <tr>
                            <th>Waktu Transaksi</th>
                            <th>Pelanggan</th>
                            <th class="text-center">Total Item</th>
                            <th>Total Bayar</th>
                            <th>Metode</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_penj)->format('d M Y') }}</td>
                            <td>{{ $row->pelanggan->nama_pelanggan ?? '-' }}</td>
                            <td class="text-center font-semibold text-sm">{{ $row->total_prod }} pcs</td>
                            <td class="text-amber-600 font-bold">Rp {{ number_format($row->subtotal_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-neutral badge-outline badge-xs font-bold uppercase p-2">{{ $row->metode_pembayaran }}</span>
                            </td>
                            <td class="flex justify-center items-center gap-2">
                                <button type="button" class="btn btn-warning btn-outline btn-xs font-bold uppercase" onclick="document.getElementById('modal_detail_{{ $row->id }}').showModal()">
                                    Detail
                                </button>
                                <form action="{{ route('admin.penjualan.pelanggan.destroy', $row->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-error">Batalkan</button>
                                </form>
                            <dialog id="modal_detail_{{ $row->id }}" class="modal text-left">
                            <div class="modal-box w-11/12 max-w-2xl whitespace-normal">
                                <h3 class="font-bold text-lg border-b pb-2 text-warning italic">Rincian Nota Transaksi</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 my-4 text-xs bg-base-200 p-3 rounded-lg">
                                <div><strong>Pelanggan:</strong> {{ $row->pelanggan->nama_pelanggan ?? 'Umum' }} ({{ $row->pelanggan->kode_pelanggan ?? '-' }})</div>
                                <div><strong>Waktu Transaksi:</strong> {{ \Carbon\Carbon::parse($row->tanggal_penj)->translatedFormat('d F Y') }}</div>
                                
                                <div>
                                    <strong>Metode Bayar:</strong> 
                                    <span class="uppercase font-bold text-succes">
                                        @if(in_array(strtolower($row->metode_pembayaran), ['hutang', 'tempo']))
                                            Bayar Nanti (Tempo)
                                        @else
                                            {{ $row->metode_pembayaran }}
                                        @endif
                                    </span>
                                </div>

                                <div>
                                    <strong>Status Pembayaran:</strong> 
                                    @if(in_array(strtolower($row->metode_pembayaran), ['hutang', 'tempo']))
                                        <span class="text-error font-bold animate-pulse">🔴 BELUM LUNAS</span>
                                    @else
                                        <span class="text-success font-bold">🟢 LUNAS</span>
                                    @endif
                                </div>
                            </div>

                                <div class="overflow-x-auto border rounded-lg">
                                    <table class="table table-compact w-full text-xs">
                                        <thead>
                                            <tr class="bg-base-300">
                                                <th class="text-right">Kode Penjualan</th>
                                                <th>Nama Produk / Varian</th>
                                                <th class="text-center">Qty Beli</th>
                                                <th class="text-right">Harga Satuan</th>
                                                <th class="text-right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($row->detail_penjualan)
                                                @foreach($row->detail_penjualan as $detail)
                                                <tr>
                                                    <td class="font-semibold text-gray-800">
                                                        {{ $detail->penjualan->kode_penjualan ?? '' }} 
                                                    </td>
                                                    <td class="font-semibold text-gray-800">
                                                        {{ $detail->produk->kategori ?? 'Produk' }} - {{ $detail->produk->varian ?? 'Terhapus' }}
                                                    </td>
                                                    <td class="text-center font-bold">{{ $detail->jumlah_produk }} pcs</td>
                                                    <td class="text-right">
                                                        Rp {{ number_format($detail->produk->harga_jual ?? 0, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-right font-bold text-amber-600">
                                                        Rp {{ number_format($detail->total_harga, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                            <tr class="bg-base-200 font-bold text-sm">
                                                <td colspan="3" class="text-right">TOTAL BELANJA:</td>
                                                <td colspan="3" class="text-right text-warning">
                                                    Rp {{ number_format($row->subtotal_harga, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="modal-action border-t pt-2">
                                    <form method="dialog">
                                        <button class="btn btn-sm btn-neutral">Tutup</button>
                                    </form>
                                </div>
                            </div>
                        </dialog>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4 p-4">
                    {{ $history->links() }}
                </div>
            </div>
        </div>
    </div>
    <dialog id="modal_tambah_pelanggan" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Daftarkan Pelanggan Baru</h3>
            <form action="{{ route('admin.pelanggan.store.ajax') }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <div class="form-control">
                        <label class="label-text">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" class="input input-bordered w-full" required>
                    </div>
                    <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">No. HP</span></label>
                            <input type="tel" name="no_hp" 
                                value="{{ old('no_hp') }}" 
                                placeholder="0812..." 
                                class="input input-bordered w-full input-sm" 
                                pattern="\d{11,13}" 
                                title="Masukkan angka saja, minimal 11 digit dan maksimal 13 digit"
                                minlength="11" maxlength="13" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                required>
                        </div>
                    <div class="form-control">
                        <label class="label-text">Alamat</label>
                        <input type="text" name="alamat_pelanggan" class="input input-bordered w-full">
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="modal_tambah_pelanggan.close()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pelanggan</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function toggleQty(checkbox) {
            const row = checkbox.closest('tr');
            const qtyField = row.querySelector('.qty-field');
            qtyField.disabled = !checkbox.checked;
            if (checkbox.checked) qtyField.value = 1;
        }
    </script>
</x-app-layout>