<x-app-layout>
    <div class="p-6 space-y-8">
        <div>
            <h2 class="text-2xl font-bold italic text-gray-800">Manajemen Penjualan Mitra Toko</h2>
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

        <form action="{{ route('admin.penjualan.mitra.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf

            <div class="card bg-base-100 border shadow-sm p-5 space-y-4 h-fit">
                <h3 class="font-bold text-base border-b pb-2 text-warning">🛒 Detail Kasir</h3>
                
                <div class="form-control">
                    <label class="label-text font-semibold mb-1 text-xs">Nama Mitra</label>
                    <select name="mitra_id" class="select select-bordered select-sm w-full" required>
                        <option value="" disabled selected>-- Pilih Mitra --</option>
                        @foreach($mitra as $p)
                            <option value="{{ $p->id }}" {{ old('mitra_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_mitra }} ({{ $p->kode_mitra }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control">
                    <label class="label-text font-semibold mb-1 text-xs">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="select select-bordered select-sm w-full" required>
                        <option value="" disabled selected>-- Pilih Pembayaran --</option>
                        <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash / Tunai</option>
                        <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="hutang" {{ old('metode_pembayaran') == 'hutang' ? 'selected' : '' }}>Hutang</option>

                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-warning btn-block text-white font-bold btn-sm shadow-sm">
                        Simpan Transaksi
                    </button>
                </div>
            </div>

            <div class="card bg-base-100 border shadow-sm p-5 lg:col-span-2 space-y-4">
                <h3 class="font-bold text-base border-b pb-2 text-neutral">Pilih Varian Produk</h3>
                <div class="overflow-x-auto max-h-60">
                    <table class="table table-compact w-full">
                        <thead class="sticky top-0 bg-base-100 z-10">
                            <tr class="bg-base-200 text-xs">
                                <th class="w-10">Pilih</th>
                                <th>Item Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="w-24">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produk as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" name="produk_id[]" value="{{ $item->id }}" class="checkbox checkbox-sm checkbox-warning" onchange="toggleQty(this)">
                                </td>
                                <td class="font-bold text-sm text-gray-800">{{ $item->kategori }} - {{ $item->varian }} - {{ $item->ukuran }}</td>
                                <td class="text-amber-600 font-semibold text-xs">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                <td class="opacity-70 text-xs">{{ $item->stok }} ready</td>
                                <td>
                                    <input type="number" name="jumlah_produk[]" min="1" max="{{ $item->stok }}" placeholder="0" class="input input-bordered input-sm w-20 qty-field" disabled>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <hr class="border-gray-200">

        <div class="space-y-3">
            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                Riwayat Transaksi Penjualan
            </h3>
            <form method="GET" action="{{ route('admin.penjualan.mitra.index') }}" class="flex flex-wrap gap-4 items-end mb-4 bg-base-200 p-4 rounded-xl">
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
                    <a href="{{ route('admin.penjualan.mitra.index') }}" class="btn btn-sm btn-ghost">Reset</a>
                </div>
            </form>
            <div class="overflow-x-auto border rounded-xl bg-base-100 shadow-sm">
                <table class="table table-zebra">
                    <thead class="bg-base-200 text-gray-700">
                        <tr>
                            <th>Waktu Transaksi</th>
                            <th>Mitra</th>
                            <th class="text-center">Total Item</th>
                            <th>Total Bayar</th>
                            <th>Metode</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $row)
                        <tr>
                            <td class="font-medium text-gray-600 text-sm">
                                {{ \Carbon\Carbon::parse($row->tanggal_penj)->translatedFormat('d M Y') }}
                            </td>
                            <td>
                                <div class="font-bold text-gray-800">{{ $row->mitra->nama_mitra ?? 'Umum/Terhapus' }}</div>
                                <div class="text-xs font-mono text-amber-600 font-semibold">{{ $row->mitra->kode_mitra ?? '-' }}</div>
                            </td>
                            <td class="text-center font-semibold text-sm">{{ $row->total_prod }} pcs</td>
                            <td class="text-amber-600 font-bold">Rp {{ number_format($row->subtotal_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-neutral badge-outline badge-xs font-bold uppercase p-2">{{ $row->metode_pembayaran }}</span>
                            </td>
                            <td class="flex justify-center items-center gap-2">
                                <button type="button" class="btn btn-warning btn-outline btn-xs font-bold uppercase" onclick="document.getElementById('modal_detail_{{ $row->id }}').showModal()">
                                    Detail
                                </button>

                                <form action="{{ route('admin.penjualan.mitra.destroy', $row->id) }}" method="POST" class="inline m-0">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-xs text-error font-bold uppercase tracking-wider" onclick="return confirm('Batalkan transaksi ini? Stok produk otomatis dikembalikan ke gudang.')">
                                        Batalkan
                                    </button>
                                </form>

            <dialog id="modal_detail_{{ $row->id }}" class="modal text-left">
                <div class="modal-box w-11/12 max-w-2xl whitespace-normal">
                    <h3 class="font-bold text-lg border-b pb-2 text-warning italic">Rincian Nota Transaksi</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 my-4 text-xs bg-base-200 p-3 rounded-lg">
                    <div><strong>Mitra:</strong> {{ $row->mitra->nama_mitra ?? 'Umum' }} ({{ $row->mitra->kode_mitra ?? '-' }})</div>
                    <div><strong>Waktu Transaksi:</strong> {{ \Carbon\Carbon::parse($row->tanggal_penj)->translatedFormat('d F Y, H:i') }}</div>
                    
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
                                </dialog>
                                </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 opacity-50 italic text-sm">Belum ada riwayat transaksi penjualan mitra yang tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4 p-4">
                    {{ $history->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleQty(checkbox) {
            const row = checkbox.closest('tr');
            const qtyField = row.querySelector('.qty-field');
            if (checkbox.checked) {
                qtyField.disabled = false;
                qtyField.value = 1;
                qtyField.setAttribute('required', 'required');
                qtyField.focus();
            } else {
                qtyField.disabled = true;
                qtyField.value = '';
                qtyField.removeAttribute('required');
            }
        }
    </script>
</x-app-layout>