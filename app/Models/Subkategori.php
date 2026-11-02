<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subkategori extends Model
{
    protected $fillable=[
        'kategori_id',
        'nama_subkategori',
        'slug',
    ];

    public function kategori(): BelongsTo{
        return $this->belongsTo(Kategori::class);
    }
    
    public function produks(): HasMany{
        return $this->hasMany(Produk::class);
    }
}
