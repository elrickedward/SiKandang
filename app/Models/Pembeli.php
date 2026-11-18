<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    protected $table = 'pembeli';
    protected $fillable = ['nama', 'no_wa', 'alamat'];

    public function pesanan() { return $this->hasMany(Pesanan::class, 'pembeli_id'); }
}