@extends('layouts.pemilik')
@section('title', 'Rekomendasi')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Rekomendasi</h3>
    <p class="text-muted">Saran keputusan pemeliharaan berdasarkan catatan harian dan stok pakan Anda.</p>
</div>

<!-- Daftar Kartu Rekomendasi Aktif -->
<div class="row g-4 mb-5">
    @forelse($rekomendasis as $rek)
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge px-3 py-2 {{ $rek->prioritas === 'Segera' ? 'bg-danger-subtle text-danger' : ($rek->prioritas === 'Waspada' ? 'bg-warning-subtle text-warning-emphasis' : 'bg-success-subtle text-success') }}">
                        {{ $rek->prioritas }}
                    </span>
                    <form action="{{ route('rekomendasi.selesai', $rek->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Tandai Selesai</button>
                    </form>
                </div>

                <h4 class="fw-bold mt-2">{{ $rek->nama_aturan }}</h4>
                <p class="fw-semibold text-dark mb-2">{{ $rek->isi_rekomendasi }}</p>
                <div class="bg-light rounded p-3 small text-muted mb-2">
                    <strong>Alasan Data:</strong> {{ $rek->alasan_data }}
                </div>
                <small class="text-muted fst-italic">*Saran pendukung keputusan berdasarkan aturan manual, bukan diagnosis medis.</small>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4 text-center text-muted">
                Tidak ada rekomendasi aktif saat ini.
            </div>
        </div>
    @endforelse
</div>

<!-- Simulasi Estimasi Biaya & Keuntungan (IOFC) -->
<div class="card border-0 shadow-sm p-4">
    <h5 class="fw-bold mb-1">Simulasi Estimasi Biaya & Keuntungan (Income Over Feed Cost)</h5>
    <p class="text-muted small mb-4">Hitung perkiraan laba/rugi dari rencana pembelian pakan sebelum mengeluarkan biaya.</p>

    <form action="{{ route('rekomendasi.simulasi') }}" method="POST" class="row g-3">
        @csrf
        <div class="col-md-4">
            <label class="form-label fw-semibold">Nama Skenario Simulasi</label>
            <input type="text" name="nama_simulasi" class="form-control" placeholder="Contoh: Rencana Beli Pakan Minggu Ini" required>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Rencana Pakan (kg)</label>
            <input type="number" step="0.1" name="rencana_pakan" class="form-control" placeholder="140" required>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Harga Pakan / kg (Rp)</label>
            <input type="number" name="harga_pakan" class="form-control" placeholder="9500" required>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Target Ayam Dijual</label>
            <input type="number" name="ayam_dijual" class="form-control" placeholder="200" required>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Harga Jual / Ekor (Rp)</label>
            <input type="number" name="harga_jual" class="form-control" value="{{ $produks->first()->harga_satuan ?? 35000 }}" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-danger px-4">Hitung Simulasi Biaya & Keuntungan</button>
        </div>
    </form>

    @if(session('hasil_simulasi'))
        @php $sim = (object) session('hasil_simulasi'); @endphp
        <div class="alert {{ $sim->est_laba >= 0 ? 'alert-success' : 'alert-danger' }} mt-4 mb-0">
            <h6 class="fw-bold mb-2">Hasil Simulasi: {{ $sim->nama_simulasi }}</h6>
            <div class="row">
                <div class="col-md-4">Estimasi Biaya Pakan: <strong>Rp {{ number_format($sim->est_biaya, 0, ',', '.') }}</strong></div>
                <div class="col-md-4">Estimasi Pendapatan: <strong>Rp {{ number_format($sim->est_pendapatan, 0, ',', '.') }}</strong></div>
                <div class="col-md-4">Estimasi Selisih (IOFC): <strong>Rp {{ number_format($sim->est_laba, 0, ',', '.') }} ({{ $sim->est_laba >= 0 ? 'UNTUNG' : 'RUGI - Tinjau Ulang!' }})</strong></div>
            </div>
        </div>
    @endif
    
</div>
@endsection