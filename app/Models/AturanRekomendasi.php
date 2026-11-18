<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AturanRekomendasi extends Model
{
    protected $table = 'aturan_rekomendasi';
    protected $fillable = [
        'user_id', 'kode_aturan', 'nama_aturan', 'kategori', 'parameter',
        'operator', 'nilai_ambang', 'nilai_ambang_atas', 'kata_kunci_kondisi',
        'saran_tindakan', 'prioritas', 'aktif'
    ];

    public function rekomendasi() { return $this->hasMany(Rekomendasi::class, 'aturan_rekomendasi_id'); }
}