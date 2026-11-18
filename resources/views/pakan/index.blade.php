@extends('layouts.app')

@section('content')
<h5 class="mb-3">Manajemen Stok Pakan</h5>

<!-- Menampilkan Pesan Sukses -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Form Tambah Pakan -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h6 class="card-title text-primary">Tambah Pakan Baru</h6>
        <form action="{{ route('pakan.store') }}" method="POST">
            @csrf
            <div class="mb-2">
                <label class="form-label small">Nama Pakan</label>
                <input type="text" name="nama_pakan" class="form-control form-control-sm" required>
            </div>
            <div class="row g-2 mb-2">
                <div class="col-6">
                    <label class="form-label small">Satuan</label>
                    <input type="text" name="satuan" class="form-control form-control-sm" placeholder="Misal: Kg/Karung" required>
                </div>
                <div class="col-6">
                    <label class="form-label small">Stok Awal</label>
                    <input type="number" name="stok" class="form-control form-control-sm" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small">Batas Minimum (Peringatan)</label>
                <input type="number" name="batas_minimum" class="form-control form-control-sm" required>
            </div>
            <button type="submit" class="btn btn-primary btn-sm w-100">Simpan Pakan</button>
        </form>
    </div>
</div>

<!-- Daftar Stok Pakan dengan Desain Card (Bukan Tabel) agar ramah di HP -->
<h6 class="mb-3">Stok Pakan Saat Ini</h6>
@foreach($pakans as $p)
    <div class="card mb-2 shadow-sm border-0 {{ $p->stok <= $p->batas_minimum ? 'border-start border-danger border-4' : 'border-start border-success border-4' }}">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">{{ $p->nama_pakan }}</h6>
                    <small class="text-muted">Min: {{ $p->batas_minimum }} {{ $p->satuan }}</small>
                </div>
                <div class="text-end">
                    <h5 class="mb-0 {{ $p->stok <= $p->batas_minimum ? 'text-danger' : 'text-success' }}">
                        {{ $p->stok }} <span class="fs-6">{{ $p->satuan }}</span>
                    </h5>
                </div>
            </div>
            <!-- Fitur Peringatan Stok Minimum[cite: 10] -->
            @if($p->stok <= $p->batas_minimum)
                <div class="mt-2 badge bg-danger w-100">⚠️ Stok Mencapai Batas Minimum!</div>
            @endif
        </div>
    </div>
@endforeach

@endsection