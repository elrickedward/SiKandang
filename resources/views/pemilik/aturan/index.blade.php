@extends('layouts.pemilik')
@section('title', 'Aturan Rekomendasi')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Aturan Rekomendasi (Rule-Based DSS)</h3>
    <p class="text-muted">Sesuaikan ambang batas dan saran tindakan secara manual sesuai kondisi peternakan Anda.</p>
</div>

<div class="card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th width="70">Kode</th>
                    <th>Nama Aturan</th>
                    <th>Parameter & Ambang</th>
                    <th>Saran Tindakan</th>
                    <th width="130">Prioritas</th>
                    <th width="90">Aktif</th>
                    <th width="110">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aturans as $aturan)
                    <tr>
                        <form action="{{ route('aturan.update', $aturan->id) }}" method="POST">
                            @csrf @method('PUT')
                            <td class="fw-bold">{{ $aturan->kode_aturan }}</td>
                            <td>
                                <input type="text" name="nama_aturan" class="form-control form-control-sm" value="{{ $aturan->nama_aturan }}" required>
                            </td>
                            <td>
                                <div class="small text-muted mb-1">{{ $aturan->parameter }} ({{ $aturan->operator }})</div>
                                @if($aturan->nilai_ambang !== null)
                                    <div class="d-flex gap-1">
                                        <input type="number" step="0.1" name="nilai_ambang" class="form-control form-control-sm" value="{{ $aturan->nilai_ambang }}">
                                        @if($aturan->operator === 'between')
                                            <input type="number" step="0.1" name="nilai_ambang_atas" class="form-control form-control-sm" value="{{ $aturan->nilai_ambang_atas }}">
                                        @endif
                                    </div>
                                @elseif($aturan->kata_kunci_kondisi !== null)
                                    <input type="text" name="kata_kunci_kondisi" class="form-control form-control-sm" value="{{ $aturan->kata_kunci_kondisi }}">
                                @else
                                    <span class="small text-muted">Otomatis dari riwayat</span>
                                @endif
                            </td>
                            <td>
                                <textarea name="saran_tindakan" rows="2" class="form-control form-control-sm" required>{{ $aturan->saran_tindakan }}</textarea>
                            </td>
                            <td>
                                <select name="prioritas" class="form-select form-select-sm">
                                    <option value="Segera" {{ $aturan->prioritas === 'Segera' ? 'selected' : '' }}>Segera</option>
                                    <option value="Waspada" {{ $aturan->prioritas === 'Waspada' ? 'selected' : '' }}>Waspada</option>
                                    <option value="Aman" {{ $aturan->prioritas === 'Aman' ? 'selected' : '' }}>Aman</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" name="aktif" class="form-check-input" {{ $aturan->aktif ? 'checked' : '' }}>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-danger btn-sm w-100">Simpan</button>
                            </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection