<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanHarian extends Model
{
    protected $table = 'catatan_harian';
    protected $fillable = ['kandang_id', 'pakan_id', 'tanggal', 'jumlah_ayam', 'kematian', 'pakan_terpakai', 'kondisi'];

    public function kandang() { return $this->belongsTo(Kandang::class, 'kandang_id'); }
    public function pakan() { return $this->belongsTo(Pakan::class, 'pakan_id'); }
}