<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Pembeli;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BerandaController extends Controller
{
    public function index()
    {
        $produks = Produk::orderBy('nama_produk')->get();
        return view('beranda', compact('produks'));
    }

    public function pesan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_wa' => 'required|string|max:25',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|string|max:100',
            'items' => 'required|array',
        ]);

        // Filter produk dengan jumlah > 0
        $itemDipilih = collect($request->items)->filter(fn ($qty) => (int) $qty > 0);

        if ($itemDipilih->isEmpty()) {
            return back()->with('error', 'Silakan pilih minimal 1 produk dengan jumlah lebih dari 0.');
        }

        try {
            $pesanan = DB::transaction(function () use ($request, $itemDipilih) {
                $totalHarga = 0;
                $rincian = [];

                foreach ($itemDipilih as $produkId => $qty) {
                    $qty = (int) $qty;
                    $produk = Produk::lockForUpdate()->findOrFail($produkId);

                    // Validasi sisi server: tolak jika jumlah melebihi stok
                    if ($qty > $produk->stok) {
                        throw new \Exception("Jumlah pesanan {$produk->nama_produk} ({$qty}) melebihi stok tersedia ({$produk->stok}).");
                    }

                    $subtotal = $produk->harga_satuan * $qty;
                    $totalHarga += $subtotal;

                    $rincian[] = [
                        'produk' => $produk,
                        'jumlah' => $qty,
                        'harga_satuan' => $produk->harga_satuan,
                        'subtotal' => $subtotal,
                    ];
                }

                $pembeli = Pembeli::create([
                    'nama' => $request->nama,
                    'no_wa' => $request->no_wa,
                    'alamat' => $request->alamat,
                ]);

                $pesanan = Pesanan::create([
                    'pembeli_id' => $pembeli->id,
                    'nomor_pesanan' => 'SK-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                    'tanggal_pesanan' => now(),
                    'total_harga' => $totalHarga,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'status' => 'Baru',
                ]);

                foreach ($rincian as $item) {
                    DetailPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'produk_id' => $item['produk']->id,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    // Kurangi stok produk
                    $item['produk']->decrement('stok', $item['jumlah']);
                }

                return $pesanan;
            });

            return redirect()->route('beranda')->with('pesanan_sukses', $pesanan->load('pembeli', 'detailPesanan.produk'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}