<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $fillable = ['pembeli_id', 'user_id', 'nomor_pesanan', 'tanggal_pesanan', 'total_harga', 'metode_pembayaran', 'status'];

    public function pembeli() { return $this->belongsTo(Pembeli::class, 'pembeli_id'); }
    public function detailPesanan() { return $this->hasMany(DetailPesanan::class, 'pesanan_id'); }
}