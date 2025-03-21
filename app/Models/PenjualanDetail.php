<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    protected $table = 'penjualan_detail';
    protected $primaryKey = 'id_penjualan_detail';
    protected $guarded = [];

    public function produk(){
        return $this->hasOne(Produk::class, 'id_produk', 'id_produk');
    }

    public function penjualan(){
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id_penjualan');
    }
}
