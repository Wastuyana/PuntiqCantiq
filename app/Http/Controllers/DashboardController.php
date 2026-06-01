<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $categories = DB::table('produk')->distinct()->pluck('kategori');
        $products = DB::table('produk')->where('kategori', $categories->first())->get();

        $rekap = DB::table('batch_hasil')
            ->join('batch', 'batch_hasil.batch_id', '=', 'batch.id')->where('batch.status', 'selesai')
            ->whereMonth('batch.tanggal_produksi', $bulan)
            ->whereYear('batch.tanggal_produksi', $tahun)
            ->selectRaw('SUM(hasil_target) as target, SUM(hasil_aktual) as aktual')
            ->first();

        $totalTarget = $rekap->target ?? 0;
        $totalAktual = $rekap->aktual ?? 0;
        $efisiensiHasilProd = $totalTarget > 0 ? ($totalAktual / $totalTarget) * 100 : 0;

        $biayaAktual = DB::table('batch')->where('status', 'selesai')->whereMonth('tanggal_produksi', $bulan)->whereYear('batch.tanggal_produksi', $tahun)
            ->sum('total_biaya');

        $biayaStandar = DB::table('batch_hasil')
            ->join('batch', 'batch_hasil.batch_id', '=', 'batch.id')
            ->join('produk', 'batch_hasil.produk_id', '=', 'produk.id')
            ->where('batch.status', 'selesai')
            ->whereMonth('batch.tanggal_produksi', $bulan)
            ->whereYear('batch.tanggal_produksi', $tahun)
            ->sum(DB::raw('produk.hpp_standar * batch_hasil.hasil_aktual'));

        $efisiensiBiayaProd = ($biayaStandar > 0) ? ($biayaAktual / $biayaStandar) * 100 : 0;

        $proporsiBiaya = DB::table('batch')
            ->whereMonth('tanggal_produksi', $bulan)->whereYear('batch.tanggal_produksi', $tahun)
            ->selectRaw('SUM(biaya_bahan) as bahan, SUM(biaya_tenagakerja) as tenaga, SUM(biaya_overhead) as overhead,
            SUM(biaya_bahan + biaya_tenagakerja + biaya_overhead) as total ')
            ->first();

        $penjualanPerVarian = \App\Models\DetailPenjualan::with('produk')
        ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
        ->whereMonth('penjualan.tanggal_penj', $bulan)
        ->whereYear('penjualan.tanggal_penj', $tahun)
        ->get()
        ->groupBy('produk.varian')
        ->map(function ($group) {
            return $group->sum('jumlah_produk');
        });

        $labels = $penjualanPerVarian->keys();
        $dataSales = $penjualanPerVarian->values();

        return view('owner.dashboard', array_merge(
            compact('efisiensiHasilProd', 'efisiensiBiayaProd', 'proporsiBiaya', 'categories', 'products', 'bulan', 'tahun', 'labels', 'dataSales'),
            ['totalAktual' => $totalAktual, 'totalTarget' => $totalTarget, 'selisihKumulatif' => $totalAktual - $totalTarget]
        )); 
    }

    public function getHppTrend(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);
        $dataHpp = DB::table('batch_hasil')
            ->join('produk', 'batch_hasil.produk_id', '=', 'produk.id')
            ->join('batch', 'batch_hasil.batch_id', '=', 'batch.id')
            ->where('produk.kategori', $request->kategori)
            ->whereYear('batch.tanggal_produksi', $tahun)
            ->when($request->produk_id !== 'all', fn($q) => $q->where('produk.ukuran', $request->produk_id))
            ->selectRaw("produk.varian, MONTH(batch.tanggal_produksi) as bulan, AVG(batch_hasil.hpp_aktual) as rata_hpp")
            ->groupBy('varian', 'bulan')->orderBy('bulan')->get();

        $colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];

        $datasets = $dataHpp->groupBy('varian')->values()->map(function ($items, $i) use ($colors) {
            $monthlyData = array_fill(0, 12, 0);
            foreach ($items as $item) {
                $monthlyData[$item->bulan - 1] = round($item->rata_hpp, 2);
            }
            return [
                'label' => $items->first()->varian,
                'data' => $monthlyData,
                'borderColor' => $colors[$i % 5],
                'backgroundColor' => $colors[$i % 5],
                'tension' => 0.4
            ];
        });

        return response()->json([
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'datasets' => $datasets
        ]);
    }

    public function getProdukByKategori(Request $request)
    {
        return response()->json(DB::table('produk')->where('kategori', $request->kategori)->select('id', 'ukuran')->get());
    }

    public function markAsRead($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notifikasi berhasil ditandai telah dibaca.');
    }
}
