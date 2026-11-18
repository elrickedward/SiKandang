<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kandang extends Model
{
    protected $table = 'kandang';
    protected $fillable = ['user_id', 'nama_kandang', 'jenis_ayam', 'populasi_awal', 'kapasitas', 'tanggal_masuk'];

    public function pemilik() { return $this->belongsTo(User::class, 'user_id'); }
    public function catatanHarian() { return $this->hasMany(CatatanHarian::class, 'kandang_id'); }
    public function riwayatKesehatan() { return $this->hasMany(RiwayatKesehatan::class, 'kandang_id'); }
    public function rekomendasi() { return $this->hasMany(Rekomendasi::class, 'kandang_id'); }
}