<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $fillable = ['user_id', 'nama_produk', 'satuan', 'harga_satuan', 'stok', 'foto', 'deskripsi'];

    public function detailPesanan() { return $this->hasMany(DetailPesanan::class, 'produk_id'); }
}