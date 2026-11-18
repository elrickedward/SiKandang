@extends('layouts.pemilik')
@section('title', 'Produk dan Harga')

@section('content')
<h3 class="fw-bold mb-4">Produk dan Harga</h3>

<!-- Form Tambah Produk -->
<div class="card border-0 shadow-sm p-4 mb-4">
    <h6 class="fw-bold mb-3">Tambah Produk Katalog</h6>
    <form action="{{ route('produk.store') }}" method="POST" class="row g-3">
        @csrf
        <div class="col-md-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Ayam Utuh" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Satuan</label>
            <input type="text" name="satuan" class="form-control" value="ekor" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Harga Satuan (Rp)</label>
            <input type="number" name="harga_satuan" class="form-control" placeholder="35000" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Stok Tersedia</label>
            <input type="number" name="stok" class="form-control" placeholder="50" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-danger w-100">Tambah</button>
        </div>
    </form>
</div>

<!-- Tabel Daftar Produk -->
<div class="card border-0 shadow-sm p-4">
    <h6 class="fw-bold mb-3">Daftar Produk Aktif (Tampil di Halaman Pembeli)</h6>
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Produk</th>
                    <th>Satuan</th>
                    <th>Harga Satuan (Rp)</th>
                    <th>Stok</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produks as $produk)
                    <tr>
                        <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                            @csrf @method('PUT')
                            <td><input type="text" name="nama_produk" class="form-control form-control-sm" value="{{ $produk->nama_produk }}" required></td>
                            <td><input type="text" name="satuan" class="form-control form-control-sm" value="{{ $produk->satuan }}" required></td>
                            <td><input type="number" name="harga_satuan" class="form-control form-control-sm" value="{{ (int)$produk->harga_satuan }}" required></td>
                            <td><input type="number" name="stok" class="form-control form-control-sm" value="{{ $produk->stok }}" required></td>
                            <td class="d-flex gap-1">
                                <button type="submit" class="btn btn-success btn-sm">Update</button>
                        </form>
                        <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                        </form>
                            </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection