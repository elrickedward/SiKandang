<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Rekomendasi;
use App\Models\SimulasiBiaya;
use App\Services\RekomendasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    public function index(RekomendasiService $dss)
    {
        $dss->evaluasiSemua();

        $rekomendasis = Rekomendasi::with('kandang')
            ->where('status', 'aktif')
            ->orderByRaw("FIELD(prioritas, 'Segera', 'Waspada', 'Aman')")
            ->latest('tanggal')
            ->get();

        $produks = Produk::all();
        $simulasiTerbaru = SimulasiBiaya::latest()->take(5)->get();

        return view('pemilik.rekomendasi.index', compact('rekomendasis', 'produks', 'simulasiTerbaru'));
    }

    public function tandaiSelesai(Rekomendasi $rekomendasi)
    {
        $rekomendasi->update(['status' => 'selesai']);
        return back()->with('success', 'Rekomendasi ditandai selesai.');
    }

    public function simulasi(Request $request)
    {
        $request->validate([
            'nama_simulasi' => 'required|string|max:255',
            'rencana_pakan' => 'required|numeric|min:0',
            'harga_pakan' => 'required|numeric|min:0',
            'ayam_dijual' => 'required|integer|min:1',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $estBiaya = $request->rencana_pakan * $request->harga_pakan;
        $estPendapatan = $request->ayam_dijual * $request->harga_jual;
        $estLaba = $estPendapatan - $estBiaya;

        $hasil = SimulasiBiaya::create([
            'user_id' => Auth::id(),
            'nama_simulasi' => $request->nama_simulasi,
            'tanggal' => now()->toDateString(),
            'rencana_pakan' => $request->rencana_pakan,
            'harga_pakan' => $request->harga_pakan,
            'ayam_dijual' => $request->ayam_dijual,
            'harga_jual' => $request->harga_jual,
            'est_biaya' => $estBiaya,
            'est_pendapatan' => $estPendapatan,
            'est_laba' => $estLaba,
        ]);

        return back()->with('hasil_simulasi', $hasil);
    }
}