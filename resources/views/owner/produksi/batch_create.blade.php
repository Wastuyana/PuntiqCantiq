<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Buat Rencana Batch Produksi Baru</h2>
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
                        </div>
                    </div>

                    <div class="card bg-base-100 shadow-xl border border-base-200">
                        <div class="card-body">
                            <h3 class="font-bold text-lg mb-4 border-b pb-2">Daftar Produk & Target</h3>
                            <p class="text-sm opacity-60 mb-4 italic">*Pilih satu atau lebih varian yang akan diproduksi
                                dalam batch ini.</p>

                            <div class="grid grid-cols-1 gap-3">
                                @foreach ($produks as $p)
                                    @php
                                        $isRecommended = in_array((string) $p->id, $rekomendasiIds ?? []);
                                        $targetValue = $rekomendasiTarget[$p->id] ?? '';
                                    @endphp
                                    <div
                                        class="flex items-center gap-4 bg-base-50 p-4 rounded-xl border {{ $isRecommended ? 'border-primary bg-primary/5' : 'border-base-200' }} hover:border-primary transition-all">
                                        <input type="checkbox" name="produk_ids[]" value="{{ $p->id }}"
                                            class="checkbox checkbox-primary product-check"
                                            {{ $isRecommended ? 'checked' : '' }}
                                            data-resep="{{ json_encode($p->bom) }}" onchange="updateEstimasi()">

                                        <div class="flex-1">
                                            <span class="font-bold text-sm block">{{ $p->kategori }} -
                                                {{ $p->varian }} - {{ $p->ukuran }}</span>
                                            @if ($isRecommended)
                                                <span class="badge badge-primary badge-xs ml-1">Rekomendasi</span>
                                            @endif
                                        </div>

                                        <div class="w-32">
                                            <label class="label p-0 mb-1">
                                                <span class="text-[10px] uppercase font-bold opacity-50">Target
                                                    (Pcs)
                                                </span>
                                            </label>
                                            <input type="number" name="hasil_target[{{ $p->id }}]"
                                                class="input input-bordered input-sm w-full target-input {{ $isRecommended ? 'border-primary' : '' }}"
                                                placeholder="0" {{ $isRecommended ? '' : 'disabled' }}
                                                value="{{ $targetValue }}" oninput="updateEstimasi()">
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
                                <div class="bg-primary-focus/10 p-4 rounded-lg">
                                    <p class="text-[10px] font-bold mb-2 tracking-widest text-primary">TOTAL KEBUTUHAN
                                        BAHAN:</p>
                                    <ul id="daftar_bahan" class="text-sm space-y-2"></ul>
                                </div>

                                <div
                                    class="alert alert-warning bg-warning/20 text-warning-content border border-warning/30 text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-4 w-4"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span>Pastikan persediaan bumbu dan pisang mencukupi sebelum memulai proses produksi
                                        di dapur.</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-full mt-6 shadow-lg">Simpan Rencana
                                Batch</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        window.onload = function() {
            updateEstimasi();
        };

        function updateEstimasi() {
            const checkboxes = document.querySelectorAll('.product-check');
            const listBahan = document.getElementById('daftar_bahan');
            const boxPreview = document.getElementById('preview_resep');
            const emptyState = document.getElementById('empty_state');

            const totalKebutuhan = {};
            const satuanBahan = {};
            const stokGudang = {}; // Tempat menampung stok riil dari database
            let adaYangDipilih = false;

            checkboxes.forEach(cb => {
                const row = cb.closest('.flex');
                const inputTarget = row.querySelector('.target-input');

                if (cb.checked) {
                    adaYangDipilih = true;
                    inputTarget.disabled = false;
                    inputTarget.classList.add('border-primary');

                    const targetValue = parseFloat(inputTarget.value) || 0;
                    const rawResep = cb.getAttribute('data-resep');
                    const dataResep = rawResep ? JSON.parse(rawResep) : [];

                    dataResep.forEach(item => {
                        const nama = item.bahan_baku.nama;
                        const takaranGramMl = parseFloat(item.jumlah_kebutuhan || 0);
                        const satuanMaster = item.bahan_baku.satuan;

                        // Ambil data stok gudang asli (stok di DB sudah dalam skala besar seperti kg/liter)
                        const stokAsliGudang = parseFloat(item.bahan_baku.stok || 0);

                        // Kalkulasi kebutuhan kotor awal (masih skala gram/ml)
                        const kebutuhanKotor = takaranGramMl * targetValue;

                        if (!totalKebutuhan[nama]) {
                            totalKebutuhan[nama] = 0;
                            satuanBahan[nama] = satuanMaster;
                            stokGudang[nama] = stokAsliGudang;
                        }

                        totalKebutuhan[nama] += kebutuhanKotor;
                    });
                } else {
                    inputTarget.disabled = true;
                    inputTarget.classList.remove('border-primary');
                }
            });

            if (adaYangDipilih) {
                boxPreview.classList.remove('hidden');
                emptyState.classList.add('hidden');
                listBahan.innerHTML = '';

                let adaBahanTercetak = false;

                for (const [nama, totalGramMl] of Object.entries(totalKebutuhan)) {
                    if (totalGramMl > 0) {
                        adaBahanTercetak = true;
                        const satuan = satuanBahan[nama];
                        const satuanLower = satuan.toLowerCase();
                        const stokTersedia = stokGudang[nama]; // Satuan Kg/Liter/Pcs asli gudang

                        let totalKebutuhanTampil = totalGramMl;

                        // Konversi kebutuhan dari gram/ml ke Kg/Liter jika tipe satuannya besar
                        if (['kg', 'liter', 'l'].includes(satuanLower)) {
                            totalKebutuhanTampil = totalGramMl / 1000;
                        }

                        // VALIDASI: Apakah stok gudang kurang dari kebutuhan produksi?
                        // Menggunakan batasan toleransi desimal halus (0.00001) agar terhindar dari bug floating-point JavaScript
                        const apakahStokKurang = (stokTersedia - totalKebutuhanTampil) < -0.00001;

                        // Format angka desimal Indonesia
                        const totalFormatted = Number(totalKebutuhanTampil.toFixed(2)).toLocaleString('id-ID');
                        const stokFormatted = Number(stokTersedia.toFixed(2)).toLocaleString('id-ID');

                        const li = document.createElement('li');

                        if (apakahStokKurang) {
                            li.className =
                                "flex flex-col py-2 border-b border-error/20 text-error font-medium";
                            li.innerHTML = `
                            <div class="flex justify-between items-center w-full">
                                <span>${nama}</span>
                                <span class="font-black text-sm">${totalFormatted} <span class="text-xs font-normal">${satuan}</span></span>
                            </div>
                            <div class="text-[11px] text-right text-error/80 mt-0.5">
                                Stok kurang! (Tersedia: ${stokFormatted} ${satuan})
                            </div>
                        `;
                        } else {
                            li.className = "flex justify-between items-center py-2 border-b border-base-200 opacity-90";
                            li.innerHTML = `
                            <span>${nama}</span>
                            <span class="font-bold text-sm">${totalFormatted} <span class="text-xs font-normal opacity-60">${satuan}</span></span>
                        `;
                        }

                        listBahan.appendChild(li);
                    }
                }

                if (!adaBahanTercetak) {
                    const li = document.createElement('li');
                    li.className = "text-center text-xs italic opacity-60 py-2";
                    li.innerText = "Masukkan jumlah target untuk melihat kalkulasi bahan.";
                    listBahan.appendChild(li);
                }
            } else {
                boxPreview.classList.add('hidden');
                emptyState.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>
