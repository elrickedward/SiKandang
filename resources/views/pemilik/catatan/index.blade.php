@extends('layouts.pemilik')
@section('title', 'Catatan Harian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Catatan Harian & Kesehatan</h3>
    <button onclick="window.print()" class="btn btn-outline-dark btn-sm no-print"><i class="bi bi-printer me-1"></i> Cetak Laporan</button>
</div>

<div class="row g-4 mb-4 no-print">
    <!-- Form Catatan Harian -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold mb-3">Input Kegiatan Harian Kandang</h6>
            <form action="{{ route('catatan.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Kandang</label>
                    <select name="kandang_id" class="form-select" required>
                        @foreach($kandangs as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kandang }} ({{ $k->jenis_ayam }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Pakan Digunakan</label>
                    <select name="pakan_id" class="form-select" required>
                        @foreach($pakans as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_pakan }} (Stok: {{ $p->stok_saat_ini }} {{ $p->satuan }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pakan Terpakai (kg)</label>
                    <input type="number" step="0.1" name="pakan_terpakai" class="form-control" placeholder="Contoh: 14" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah Ayam Hidup</label>
                    <input type="number" name="jumlah_ayam" class="form-control" placeholder="245" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kematian (ekor)</label>
                    <input type="number" name="kematian" class="form-control" value="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kondisi Kandang</label>
                    <input type="text" name="kondisi" class="form-control" value="Normal" placeholder="Normal / nafsu makan turun" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-danger">Simpan Catatan & Analisis Rekomendasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Riwayat Kesehatan -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold mb-3">Catat Gejala / Riwayat Kesehatan</h6>
            <form action="{{ route('kesehatan.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Kandang</label>
                    <select name="kandang_id" class="form-select" required>
                        @foreach($kandangs as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kandang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Gejala / Penyakit</label>
                    <input type="text" name="gejala_penyakit" class="form-control" placeholder="Contoh: Flu / Ngorok (CRD)" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Tindakan yang Dilakukan</label>
                    <textarea name="tindakan" rows="2" class="form-control" placeholder="Contoh: Isolasi ayam sakit dan pemberian vitamin" required></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-dark">Simpan Riwayat Kesehatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabel Riwayat Catatan Harian -->
<div class="card border-0 shadow-sm p-4 mb-4">
    <h6 class="fw-bold mb-3">Riwayat Catatan Harian Kandang</h6>
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Kandang</th>
                    <th>Ayam Hidup</th>
                    <th>Kematian</th>
                    <th>Pakan</th>
                    <th>Terpakai</th>
                    <th>Kondisi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($catatans as $c)
                    <tr>
                        <td>{{ $c->tanggal }}</td>
                        <td>{{ $c->kandang->nama_kandang }}</td>
                        <td>{{ $c->jumlah_ayam }} ekor</td>
                        <td>{{ $c->kematian }} ekor</td>
                        <td>{{ $c->pakan->nama_pakan }}</td>
                        <td>{{ $c->pakan_terpakai }} {{ $c->pakan->satuan }}</td>
                        <td>{{ $c->kondisi }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Belum ada catatan harian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Tabel Riwayat Kesehatan -->
<div class="card border-0 shadow-sm p-4">
    <h6 class="fw-bold mb-3">Riwayat Kesehatan Kandang</h6>
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Kandang</th>
                    <th>Gejala / Penyakit</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatKesehatan as $rk)
                    <tr>
                        <td>{{ $rk->tanggal }}</td>
                        <td>{{ $rk->kandang->nama_kandang }}</td>
                        <td class="fw-semibold">{{ $rk->gejala_penyakit }}</td>
                        <td>{{ $rk->tindakan }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada riwayat penyakit tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection