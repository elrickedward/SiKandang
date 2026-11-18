@extends('layouts.pemilik')
@section('title', 'Pesanan Masuk')

@section('content')
<h3 class="fw-bold mb-4">Pesanan Masuk</h3>

<div class="card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Pesanan & Tanggal</th>
                    <th>Data Pembeli</th>
                    <th>Rincian Item</th>
                    <th>Total & Pembayaran</th>
                    <th>Konfirmasi WA</th>
                    <th width="170">Status Pesanan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $pesanan)
                    @php
                        $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $pesanan->pembeli->no_wa));
                        $pesanWa = "Halo Kak {$pesanan->pembeli->nama}, pesanan Anda ({$pesanan->nomor_pesanan}) di SiKandang sebesar Rp " . number_format($pesanan->total_harga, 0, ',', '.') . " telah kami terima.";
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $pesanan->nomor_pesanan }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d M Y H:i') }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $pesanan->pembeli->nama }}</div>
                            <div class="small text-muted">WA: {{ $pesanan->pembeli->no_wa }}</div>
                            <div class="small text-muted">{{ $pesanan->pembeli->alamat }}</div>
                        </td>
                        <td>
                            <ul class="mb-0 ps-3 small">
                                @foreach($pesanan->detailPesanan as $detail)
                                    <li>{{ $detail->produk->nama_produk }} ({{ $detail->jumlah }} {{ $detail->produk->satuan }}) - Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <div class="fw-bold text-danger">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                            <small class="text-muted">{{ $pesanan->metode_pembayaran }}</small>
                        </td>
                        <td>
                            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($pesanWa) }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="bi bi-whatsapp me-1"></i> Chat Pembeli
                            </a>
                        </td>
                        <td>
                            <form action="{{ route('pesanan.status', $pesanan->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    @foreach(['Baru', 'Diproses', 'Selesai', 'Dibatalkan'] as $st)
                                        <option value="{{ $st }}" {{ $pesanan->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada pesanan masuk dari pembeli.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection