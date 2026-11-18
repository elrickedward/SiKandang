<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    protected $table = 'rekomendasi';
    protected $fillable = ['kandang_id', 'aturan_rekomendasi_id', 'tanggal', 'nama_aturan', 'isi_rekomendasi', 'alasan_data', 'prioritas', 'status'];

    public function kandang() { return $this->belongsTo(Kandang::class, 'kandang_id'); }
    public function aturan() { return $this->belongsTo(AturanRekomendasi::class, 'aturan_rekomendasi_id'); }
}