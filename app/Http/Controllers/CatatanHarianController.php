<?php

namespace App\Http\Controllers;

use App\Models\CatatanHarian;
use App\Models\Kandang;
use App\Models\Pakan;
use App\Models\RiwayatKesehatan;
use App\Services\RekomendasiService;
use Illuminate\Http\Request;

class CatatanHarianController extends Controller
{
    public function index()
    {
        $kandangs = Kandang::all();
        $pakans = Pakan::all();
        $catatans = CatatanHarian::with('kandang', 'pakan')->latest('tanggal')->latest('id')->get();
        $riwayatKesehatan = RiwayatKesehatan::with('kandang')->latest('tanggal')->latest('id')->get();

        return view('pemilik.catatan.index', compact('kandangs', 'pakans', 'catatans', 'riwayatKesehatan'));
    }

    public function store(Request $request, RekomendasiService $dss)
    {
        $request->validate([
            'kandang_id' => 'required|exists:kandang,id',
            'pakan_id' => 'required|exists:pakan,id',
            'tanggal' => 'required|date',
            'jumlah_ayam' => 'required|integer|min:0',
            'kematian' => 'required|integer|min:0',
            'pakan_terpakai' => 'required|numeric|min:0',
            'kondisi' => 'required|string|max:255',
        ]);

        $pakan = Pakan::findOrFail($request->pakan_id);

        // Validasi sisi server: tolak jika pakan terpakai melebihi stok tersedia
        if ($request->pakan_terpakai > $pakan->stok_saat_ini) {
            return back()->withInput()->with('error', "Gagal! Pakan terpakai ({$request->pakan_terpakai} {$pakan->satuan}) melebihi sisa stok ({$pakan->stok_saat_ini} {$pakan->satuan}).");
        }

        CatatanHarian::create($request->only('kandang_id', 'pakan_id', 'tanggal', 'jumlah_ayam', 'kematian', 'pakan_terpakai', 'kondisi'));
        $pakan->decrement('stok_saat_ini', $request->pakan_terpakai);

        // Jalankan evaluasi aturan DSS
        $dss->evaluasiSemua($request->kandang_id);

        return back()->with('success', 'Catatan harian tersimpan, stok pakan dikurangi, dan rekomendasi diperbarui.');
    }

    public function storeKesehatan(Request $request, RekomendasiService $dss)
    {
        $request->validate([
            'kandang_id' => 'required|exists:kandang,id',
            'tanggal' => 'required|date',
            'gejala_penyakit' => 'required|string|max:255',
            'tindakan' => 'required|string',
        ]);

        $riwayat = RiwayatKesehatan::create($request->only('kandang_id', 'tanggal', 'gejala_penyakit', 'tindakan'));
        $dss->evaluasiRiwayatPenyakit($riwayat);

        return back()->with('success', 'Riwayat kesehatan berhasil dicatat.');
    }
}