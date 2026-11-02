<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Kategori;

class Produk extends Model
{
    protected $fillable=[
        'subkategori_id',
        'nama_produk',
        'slug',
        'deskripsi',
        'foto_produk',
        'harga',
        'stok',
        'berat',
        'satuan',
    ];
    
    public function subkategori(): BelongsTo{
        return $this->belongsTo(Subkategori::class);
    }

    public function promos(){
        return $this->hasMany(Promo::class);
    }
    
    public function promoAktif(){
        return $this->hasOne(Promo::class)->where('is_active', true)->whereDate('tanggal_mulai', '<=', today())
            ->whereDate('tanggal_berakhir', '>=', today())->latestOfMany();
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
    
}
