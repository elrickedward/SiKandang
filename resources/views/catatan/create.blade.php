@extends('layouts.app')

@section('content')
<h5 class="mb-3">Catatan Harian Kandang</h5>

<!-- Menampilkan Pesan Sukses -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Menampilkan Pesan Error (Tolak input jika stok kurang) -->
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Gagal!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('catatan.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label small fw-bold">Pilih Kandang</label>
                <select name="kandang_id" class="form-select form-select-sm" required>
                    <option value="">-- Pilih Kandang --</option>
                    @foreach($kandangs as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kandang }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Tanggal Catatan</label>
                <!-- Otomatis terisi tanggal hari ini -->
                <input type="date" name="tanggal_catatan" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
            </div>

            <div class="p-3 bg-light rounded mb-3 border">
                <h6 class="text-primary small fw-bold mb-2">Pemakaian Pakan</h6>
                <div class="mb-2">
                    <label class="form-label small">Jenis Pakan</label>
                    <select name="pakan_id" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Pakan --</option>
                        @foreach($pakans as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_pakan }} (Sisa Stok: {{ $p->stok }} {{ $p->satuan }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label small">Jumlah Terpakai</label>
                    <input type="number" name="jumlah_pakan" class="form-control form-control-sm" required min="1">
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label small fw-bold">Kematian Ternak</label>
                    <input type="number" name="jumlah_ternak_mati" class="form-control form-control-sm" value="0" min="0">
                </div>
                <div class="col-6">
                    <label class="form-label small fw-bold">Jumlah Telur</label>
                    <input type="number" name="jumlah_telur" class="form-control form-control-sm" value="0" min="0">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Catatan</button>
        </form>
    </div>
</div>
@endsection