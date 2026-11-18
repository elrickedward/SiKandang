<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PakanMasuk extends Model
{
    protected $table = 'pakan_masuk';
    protected $fillable = ['pakan_id', 'tanggal', 'jumlah', 'harga_satuan', 'pemasok'];

    public function pakan() { return $this->belongsTo(Pakan::class, 'pakan_id'); }
}