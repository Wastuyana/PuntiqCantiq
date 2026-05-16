<x-app-layout>
    <div class="p-6 bg-base-100 min-h-screen">
        <div class="text-sm breadcrumbs mb-4 opacity-50">
            <ul>
                <li><a href="{{ route('admin.produksi.batch.index') }}">BATCH</a></li>
                <li>PENYESUAIAN STOK</li>
            </ul>
        </div>

        <div>
            <h1 class="text-2xl font-bold text-base-content">Penyesuaian Stok</h1>
        </div>

        <div class="">
            <form action="{{ route('admin.produksi.penyesuaian.store') }}" method="POST">
                @csrf
                <input type="hidden" name="batch_id" value="{{ $batch->id }}">

                <div class="card bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body space-y-6">

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-4  p-4 rounded-xl">
                            <div>
                                <span class="text-[10px] uppercase font-bold opacity-50 block">Nomor Batch</span>
                                <span class="font-bold text-lg">{{ $batch->nomor_batch }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold opacity-50 block">Tanggal Produksi</span>
                                <span
                                    class="font-bold text-lg">{{ \Carbon\Carbon::parse($batch->tanggal_produksi)->format('d M Y') }}</span>
                            </div>
                        </div>


                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold">Pilih Produk yang Rusak</span>
                            </label>
                            <select name="produk_id" class="select select-bordered focus:border-primary w-full"
                                required>
                                <option value="" disabled selected>-- Pilih Produk dari Batch Ini --</option>
                                @foreach ($batch->batch_hasil as $item)
                                    <option value="{{ $item->produk_id }}">
                                        {{ $item->produk->kategori }} - {{ $item->produk->varian }} - {{ $item->produk->ukuran }}
                                    </option>
                                @endforeach
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-error">
                                    @error('produk_id')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold">Jumlah Barang Rusak (Pcs)</span>
                            </label>
                            <div class="join">
                                <input type="number" name="jumlah_rusak"
                                    class="input input-bordered join-item w-full focus:border-primary" placeholder="0"
                                    min="1" required>
                                <span class="btn btn-disabled join-item border-base-300">Pcs</span>
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-error">
                                    @error('jumlah_rusak')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold">Keterangan</span>
                            </label>
                            <textarea name="keterangan" class="textarea textarea-bordered h-24 focus:border-primary" placeholder="Sebab keusakan"></textarea>
                            <label class="label">
                                <span class="label-text-alt text-error">
                                    @error('keterangan')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                        </div>

                        <div class="card-actions justify-end mt-4">
                            <button type="submit" class="btn btn-outline btn-primary w-full md:w-auto px-10 shadow-lg">
                                Simpan Penyesuaian
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
