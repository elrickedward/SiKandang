<?php

namespace App\Http\Controllers;

use App\Models\CatatanHarian;
use App\Models\Kandang;
use App\Models\Pakan;
use App\Models\Pesanan;
use App\Models\Rekomendasi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $kandang = Kandang::first();
        $pakans = Pakan::all();
        $totalStokPakan = $pakans->sum('stok_saat_ini');

        $catatanTerakhir = $kandang
            ? CatatanHarian::where('kandang_id', $kandang->id)->latest('tanggal')->latest('id')->first()
            : null;

        $ayamHidup = $catatanTerakhir ? $catatanTerakhir->jumlah_ayam : ($kandang->populasi_awal ?? 0);
        $kematianHariIni = CatatanHarian::whereDate('tanggal', Carbon::today())->sum('kematian');
        $pesananBaruCount = Pesanan::where('status', 'Baru')->count();
        $pesananTerbaru = Pesanan::with('pembeli', 'detailPesanan.produk')->latest('tanggal_pesanan')->take(5)->get();

        $rekomendasiAktif = Rekomendasi::where('status', 'aktif')
            ->orderByRaw("FIELD(prioritas, 'Segera', 'Waspada', 'Aman')")
            ->latest('tanggal')
            ->get();

        // Data Grafik Pemakaian Pakan 7 Hari Terakhir
        $grafikLabels = [];
        $grafikData = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i);
            $grafikLabels[] = $tgl->translatedFormat('D, d M');
            $grafikData[] = (float) CatatanHarian::whereDate('tanggal', $tgl)->sum('pakan_terpakai');
        }

        return view('pemilik.dashboard', compact(
            'kandang', 'pakans', 'totalStokPakan', 'ayamHidup',
            'kematianHariIni', 'pesananBaruCount', 'pesananTerbaru',
            'rekomendasiAktif', 'grafikLabels', 'grafikData'
        ));
    }
}