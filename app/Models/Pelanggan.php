<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $guarded = [];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }
}