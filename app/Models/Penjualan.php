<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    protected $guarded = [];

    public function member(){
        return $this->hasOne(Member::class, 'id_member', 'id_member');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function penjualan_detail(){
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan', 'id_penjualan');
    }
}
