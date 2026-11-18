<?php

namespace App\Http\Controllers;

use App\Models\Pakan;
use App\Models\PakanMasuk;
use App\Services\RekomendasiService;
use Illuminate\Http\Request;

class PakanController extends Controller
{
    public function index()
    {
        $pakans = Pakan::all();
        $riwayatMasuk = PakanMasuk::with('pakan')->latest('tanggal')->latest('id')->get();
        return view('pemilik.pakan.index', compact('pakans', 'riwayatMasuk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pakan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'stok_saat_ini' => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
        ]);

        Pakan::create($request->only('nama_pakan', 'satuan', 'stok_saat_ini', 'stok_minimum'));
        return back()->with('success', 'Jenis pakan baru berhasil ditambahkan.');
    }

    public function storeMasuk(Request $request, RekomendasiService $dss)
    {
        $request->validate([
            'pakan_id' => 'required|exists:pakan,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0.1',
            'harga_satuan' => 'required|numeric|min:0',
            'pemasok' => 'nullable|string|max:255',
        ]);

        $masuk = PakanMasuk::create($request->only('pakan_id', 'tanggal', 'jumlah', 'harga_satuan', 'pemasok'));
        $masuk->pakan->increment('stok_saat_ini', $request->jumlah);

        $dss->evaluasiSemua();

        return back()->with('success', 'Pakan masuk dicatat dan stok otomatis bertambah.');
    }
}