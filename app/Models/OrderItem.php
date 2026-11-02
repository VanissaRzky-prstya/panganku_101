<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'produk_id',
        'harga_asli',
        'tipe_potongan',
        'besar_potongan',
        'harga_setelah_diskon',
        'qty',
        'subtotal',
    ];

    public function order(): BelongsTo{
        return $this->belongsTo(Order::class);
    }

    public function produk(): BelongsTo{
        return $this->belongsTo(Produk::class);
    }
}
