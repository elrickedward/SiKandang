<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::orderBy('nama_produk')->get();
        return view('pemilik.produk.index', compact('produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        Produk::create(array_merge($request->only('nama_produk', 'satuan', 'harga_satuan', 'stok', 'deskripsi'), [
            'user_id' => Auth::id(),
        ]));

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $produk->update($request->only('nama_produk', 'satuan', 'harga_satuan', 'stok', 'deskripsi'));
        return back()->with('success', 'Data produk diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return back()->with('success', 'Produk dihapus.');
    }
}