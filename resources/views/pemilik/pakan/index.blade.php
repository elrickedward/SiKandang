@extends('layouts.pemilik')
@section('title', 'Stok Pakan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Stok Pakan</h3>
    <button onclick="window.print()" class="btn btn-outline-dark btn-sm no-print"><i class="bi bi-printer me-1"></i> Cetak Laporan Stok</button>
</div>

<!-- Kartu Stok Per Jenis Pakan -->
<div class="row g-3 mb-4">
    @foreach($pakans as $p)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">{{ $p->nama_pakan }}</h6>
                    @if($p->stok_saat_ini <= $p->stok_minimum)
                        <span class="badge bg-danger">Di Bawah Minimum</span>
                    @else
                        <span class="badge bg-success">Stok Aman</span>
                    @endif
                </div>
                <h3 class="fw-bold text-dark mb-1">{{ $p->stok_saat_ini }} {{ $p->satuan }}</h3>
                <small class="text-muted">Batas stok minimum: {{ $p->stok_minimum }} {{ $p->satuan }}</small>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4 mb-4 no-print">
    <!-- Form Catat Pakan Masuk -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold mb-3">Catat Pakan Masuk (Pembelian)</h6>
            <form action="{{ route('pakan.masuk') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Pilih Jenis Pakan</label>
                    <select name="pakan_id" class="form-select" required>
                        @foreach($pakans as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_pakan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah (kg)</label>
                    <input type="number" step="0.1" name="jumlah" class="form-control" placeholder="50" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Harga Satuan (Rp)</label>
                    <input type="number" name="harga_satuan" class="form-control" placeholder="9500" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pemasok / Toko</label>
                    <input type="text" name="pemasok" class="form-control" placeholder="Toko Tani Jaya">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-danger">Simpan Pakan Masuk</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Tambah Jenis Pakan Baru -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold mb-3">Tambah Jenis Pakan Baru</h6>
            <form action="{{ route('pakan.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label">Nama Pakan</label>
                    <input type="text" name="nama_pakan" class="form-control" placeholder="Contoh: Konsentrat Petelur" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-control" value="kg" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok Awal</label>
                    <input type="number" step="0.1" name="stok_saat_ini" class="form-control" value="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok Minimum</label>
                    <input type="number" step="0.1" name="stok_minimum" class="form-control" value="25" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-dark">Tambah Pakan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabel Riwayat Pakan Masuk -->
<div class="card border-0 shadow-sm p-4">
    <h6 class="fw-bold mb-3">Riwayat Pakan Masuk</h6>
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Pakan</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Pemasok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatMasuk as $rm)
                    <tr>
                        <td>{{ $rm->tanggal }}</td>
                        <td>{{ $rm->pakan->nama_pakan }}</td>
                        <td class="fw-semibold">+{{ $rm->jumlah }} {{ $rm->pakan->satuan }}</td>
                        <td>Rp {{ number_format($rm->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $rm->pemasok ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat pakan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection