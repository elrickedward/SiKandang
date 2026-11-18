<?php

namespace App\Http\Controllers;

use App\Models\AturanRekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AturanRekomendasiController extends Controller
{
    public function index()
    {
        $aturans = AturanRekomendasi::orderBy('kode_aturan')->get();
        return view('pemilik.aturan.index', compact('aturans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_aturan' => 'required|string|max:20|unique:aturan_rekomendasi,kode_aturan',
            'nama_aturan' => 'required|string|max:255',
            'kategori' => 'required|in:pakan,kesehatan,keuangan',
            'parameter' => 'required|string|max:100',
            'operator' => 'required|string|max:20',
            'nilai_ambang' => 'nullable|numeric',
            'nilai_ambang_atas' => 'nullable|numeric',
            'kata_kunci_kondisi' => 'nullable|string|max:255',
            'saran_tindakan' => 'required|string',
            'prioritas' => 'required|in:Segera,Waspada,Aman',
        ]);

        AturanRekomendasi::create(array_merge(
            $request->all(),
            ['user_id' => Auth::id(), 'aktif' => true]
        ));

        return back()->with('success', 'Aturan rekomendasi baru berhasil ditambahkan.');
    }

    public function update(Request $request, AturanRekomendasi $aturan)
    {
        $request->validate([
            'nama_aturan' => 'required|string|max:255',
            'nilai_ambang' => 'nullable|numeric',
            'nilai_ambang_atas' => 'nullable|numeric',
            'kata_kunci_kondisi' => 'nullable|string|max:255',
            'saran_tindakan' => 'required|string',
            'prioritas' => 'required|in:Segera,Waspada,Aman',
        ]);

        $aturan->update([
            'nama_aturan' => $request->nama_aturan,
            'nilai_ambang' => $request->nilai_ambang,
            'nilai_ambang_atas' => $request->nilai_ambang_atas,
            'kata_kunci_kondisi' => $request->kata_kunci_kondisi,
            'saran_tindakan' => $request->saran_tindakan,
            'prioritas' => $request->prioritas,
            'aktif' => $request->has('aktif'),
        ]);

        return back()->with('success', "Aturan {$aturan->kode_aturan} berhasil diperbarui.");
    }
}