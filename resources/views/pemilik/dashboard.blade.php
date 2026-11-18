@extends('layouts.pemilik')
@section('title', 'Dasbor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Dasbor</h3>
    <span class="text-muted">Selamat datang, <strong>{{ Auth::user()->name }}</strong></span>
</div>

<!-- 4 Kartu Ringkasan Utama -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 text-center">
            <div class="text-muted small mb-1">Sisa Stok Pakan</div>
            <h4 class="fw-bold mb-2">{{ number_format($totalStokPakan, 1) }} kg</h4>
            @if($rekomendasiAktif->where('prioritas', 'Segera')->count() > 0)
                <a href="{{ route('rekomendasi.index') }}" class="badge bg-danger-subtle text-danger text-decoration-none py-2 px-3">Segera beli pakan</a>
            @else
                <span class="badge bg-success-subtle text-success py-2 px-3">Stok terpantau aman</span>
            @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 text-center">
            <div class="text-muted small mb-1">Ayam Hidup ({{ $kandang->nama_kandang ?? 'Kandang' }})</div>
            <h4 class="fw-bold mb-0">{{ $ayamHidup }} Ekor</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 text-center">
            <div class="text-muted small mb-1">Kematian Hari Ini</div>
            <h4 class="fw-bold mb-0">{{ $kematianHariIni }} Ekor</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100 text-center">
            <div class="text-muted small mb-1">Pesanan Masuk</div>
            <h5 class="fw-bold mb-2">{{ $pesananBaruCount }} pesanan baru</h5>
            <a href="{{ route('pesanan.index') }}" class="btn btn-danger btn-sm px-3">Cek Sekarang</a>
        </div>
    </div>
</div>

<!-- Grafik Pemakaian Pakan 7 Hari & Rekomendasi Aktif -->
<div class="row g-4 mb-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold mb-3">Pemakaian Pakan 7 Hari Terakhir (kg)</h6>
            <canvas id="grafikPakan" height="130"></canvas>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card border-0 shadow-sm p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Rekomendasi Aktif</h6>
                <a href="{{ route('rekomendasi.index') }}" class="small text-danger text-decoration-none">Lihat semua &rarr;</a>
            </div>
            @forelse($rekomendasiAktif->take(3) as $rek)
                <div class="border rounded p-3 mb-2">
                    <span class="badge {{ $rek->prioritas === 'Segera' ? 'bg-danger' : ($rek->prioritas === 'Waspada' ? 'bg-warning text-dark' : 'bg-success') }} mb-1">
                        {{ $rek->prioritas }}
                    </span>
                    <div class="fw-bold">{{ $rek->nama_aturan }}</div>
                    <div class="small text-muted">{{ $rek->isi_rekomendasi }}</div>
                </div>
            @empty
                <p class="text-muted small">Belum ada rekomendasi aktif. Input catatan harian untuk memicu analisis sistem.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Tabel Pesanan Terbaru -->
<div class="card border-0 shadow-sm p-4">
    <h6 class="fw-bold mb-3">Pesanan Terbaru</h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nomor Pesanan</th>
                    <th>Pembeli</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesananTerbaru as $p)
                    <tr>
                        <td class="fw-semibold">{{ $p->nomor_pesanan }}</td>
                        <td>{{ $p->pembeli->nama }}</td>
                        <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $p->status === 'Baru' ? 'bg-warning text-dark' : ($p->status === 'Selesai' ? 'bg-success' : 'bg-info') }}">
                                {{ $p->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada pesanan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('grafikPakan'), {
        type: 'bar',
        data: {
            labels: @json($grafikLabels),
            datasets: [{
                label: 'Pakan Terpakai (kg)',
                data: @json($grafikData),
                backgroundColor: '#d92525',
                borderRadius: 6
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
</script>
@endpush