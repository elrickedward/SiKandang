<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pakan extends Model
{
    protected $table = 'pakan';
    protected $fillable = ['nama_pakan', 'satuan', 'stok_saat_ini', 'stok_minimum'];

    public function pakanMasuk() { return $this->hasMany(PakanMasuk::class, 'pakan_id'); }
    public function catatanHarian() { return $this->hasMany(CatatanHarian::class, 'pakan_id'); }
}