<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $fillable=['nama_kategori','slug','foto_kategori',];
    
    public function subkategoris(): HasMany{
        return $this->hasMany(Subkategori::class);
    }
}
