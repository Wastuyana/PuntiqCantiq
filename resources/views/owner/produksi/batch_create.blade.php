<x-app-layout>
    <div class="p-6 bg-base-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Buat Batch Produksi Baru</h2>
            <a href="{{ route('owner.produksi.batch.index') }}" class="btn btn-ghost"> Kembali</a>
        </div>

        <form action="{{ route('owner.produksi.batch.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-6">
                    <div class="card bg-base-100 shadow-xl border border-base-200">
                        <div class="card-body">
                            <h3 class="font-bold text-lg mb-4 border-b pb-2">Informasi Dasar</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="label"><span class="label-text">No. Batch</span></label>
                                    <input type="text" value="{{ $generatedNoBatch }}"
                                        class="input input-bordered bg-base-200" readonly>
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-semibold">Tanggal
                                            Produksi</span></label>
                                    <input type="date" name="tanggal_produksi" value="{{ date('Y-m-d') }}"
                                        class="input input-bordered focus:border-primary" required>
                                </div>
                            </div>

                            <div class="form-control mt-4">
                                <label class="label"><span class="label-text font-semibold">Status Awal</span></label>
                                <select name="status" class="select select-bordered w-full">
                                    <option value="draft">Draft</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-base-100 shadow-xl border border-base-200">
                        <div class="card-body">
                            <h3 class="font-bold text-lg mb-4 border-b pb-2">Daftar Produk & Target</h3>
                            <p class="text-sm opacity-60 mb-4 italic">*Pilih satu atau lebih varian yang akan diproduksi
                                dalam batch ini.</p>

                            <div class="grid grid-cols-1 gap-3">
                                @foreach ($produks as $p)
                                    <div
                                        class="flex items-center gap-4 bg-base-50 p-4 rounded-xl border border-base-200 hover:border-primary transition-all">
                                        <input type="checkbox" name="produk_ids[]" value="{{ $p->id }}"
                                            class="checkbox checkbox-primary product-check"
                                            data-resep="{{ json_encode($p->bom) }}" onchange="updateEstimasi()">

                                        <div class="flex-1">
                                            <span class="font-bold text-sm block">{{ $p->kategori }}</span>
                                            <span class="text-xs opacity-70">{{ $p->varian }}</span>
                                        </div>

                                        <div class="w-32">
                                            <label class="label p-0 mb-1"><span
                                                    class="text-[10px] uppercase font-bold opacity-50">Target
                                                    (Pcs)</span></label>
                                            <input type="number" name="hasil_target[{{ $p->id }}]"
                                                class="input input-bordered input-sm w-full target-input"
                                                placeholder="0" disabled oninput="updateEstimasi()">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="card bg-base-100 shadow-xl sticky top-6">
                        <div class="card-body">
                            <h3 class="font-bold text-xl border-b border-primary-focus pb-2">Estimasi Kebutuhan</h3>

                            <div id="empty_state" class="py-10 text-center opacity-70">
                                <p class="text-sm italic">Belum ada produk dipilih</p>
                            </div>

                            <div id="preview_resep" class="hidden space-y-4">
                                <div class="bg-primary-focus/30 p-4 rounded-lg">
                                    <p class="text-[10px] font-bold mb-2 tracking-widest">Total
                                        Bahan Baku:</p>
                                    <ul id="daftar_bahan" class="text-sm space-y-2"></ul>
                                </div>

                                <div
                                    class="alert alert-warning bg-warning text-secondary-content border-none text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-4 w-4"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span>Pastikan stok di gudang cukup sebelum memulai.</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-white w-full mt-6 shadow-lg">Konfirmasi & Mulai
                                Batch</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        function updateEstimasi() {
            const checkboxes = document.querySelectorAll('.product-check');
            const listBahan = document.getElementById('daftar_bahan');
            const boxPreview = document.getElementById('preview_resep');
            const emptyState = document.getElementById('empty_state');

            const totalKebutuhan = {};
            const satuanBahan = {};
            let adaYangDipilih = false;

            checkboxes.forEach(cb => {
                const row = cb.closest('.flex');
                const inputTarget = row.querySelector('.target-input');

                if (cb.checked) {
                    adaYangDipilih = true;
                    inputTarget.disabled = false;
                    inputTarget.classList.add('border-primary');

                    const targetValue = parseFloat(inputTarget.value) || 0;
                    const dataResep = JSON.parse(cb.getAttribute('data-resep'));

                    dataResep.forEach(item => {
                        const nama = item.bahan_baku.nama;
                        const takaran = parseFloat(item.jumlah_kebutuhan || 0);
                        const satuan = item.bahan_baku.satuan;

                        totalKebutuhan[nama] = (totalKebutuhan[nama] || 0) + (takaran * targetValue);
                        satuanBahan[nama] = satuan;
                    });
                } else {
                    inputTarget.disabled = true;
                    inputTarget.value = '';
                    inputTarget.classList.remove('border-primary');
                }
            });

            if (adaYangDipilih) {
                boxPreview.classList.remove('hidden');
                emptyState.classList.add('hidden');
                listBahan.innerHTML = '';

                for (const [nama, total] of Object.entries(totalKebutuhan)) {
                    if (total > 0) {
                        const li = document.createElement('li');
                        li.className = "flex justify-between items-center py-1 border-b border-primary-focus/20";
                        li.innerHTML = `
                        <span>${nama}</span>
                        <span class="font-black">${total.toLocaleString('id-ID')} ${satuanBahan[nama]}</span>
                    `;
                        listBahan.appendChild(li);
                    }
                }
            } else {
                boxPreview.classList.add('hidden');
                emptyState.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>
