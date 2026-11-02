<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanPendapatanProduk extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'produk_id';
    public $incrementing = false;
    public $timestamps = false;

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
