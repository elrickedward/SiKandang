<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKesehatan extends Model
{
    protected $table = 'riwayat_kesehatan';
    protected $fillable = ['kandang_id', 'tanggal', 'gejala_penyakit', 'tindakan'];

    public function kandang() { return $this->belongsTo(Kandang::class, 'kandang_id'); }
}