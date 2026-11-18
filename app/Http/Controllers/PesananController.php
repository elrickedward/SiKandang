<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with('pembeli', 'detailPesanan.produk')
            ->latest('tanggal_pesanan')
            ->get();

        return view('pemilik.pesanan.index', compact('pesanans'));
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status' => 'required|in:Baru,Diproses,Selesai,Dibatalkan',
        ]);

        // Kembalikan stok produk jika pesanan dibatalkan
        if ($request->status === 'Dibatalkan' && $pesanan->status !== 'Dibatalkan') {
            foreach ($pesanan->detailPesanan as $detail) {
                $detail->produk->increment('stok', $detail->jumlah);
            }
        }

        $pesanan->update([
            'status' => $request->status,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', "Status pesanan {$pesanan->nomor_pesanan} diubah menjadi {$request->status}.");
    }
}