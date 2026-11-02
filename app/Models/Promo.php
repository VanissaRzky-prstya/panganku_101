<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'produk_id',
        'tipe_potongan',
        'besar_potongan',
        'tanggal_mulai',
        'tanggal_berakhir',
        'is_active',
    ];

    public function produk(){
        return $this->belongsTo(Produk::class);
    }
}
