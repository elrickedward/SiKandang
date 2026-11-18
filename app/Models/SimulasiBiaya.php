<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulasiBiaya extends Model
{
    protected $table = 'simulasi_biaya';
    protected $fillable = ['user_id', 'nama_simulasi', 'tanggal', 'rencana_pakan', 'harga_pakan', 'ayam_dijual', 'harga_jual', 'est_biaya', 'est_pendapatan', 'est_laba'];

    public function pemilik() { return $this->belongsTo(User::class, 'user_id'); }
}