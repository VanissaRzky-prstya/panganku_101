<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KabupatenKota extends Model
{
    protected $table = 'kabupaten_kotas';

    protected $fillable = [
        'provinsi_id',
        'nama_kabupaten_kota',
    ];

    public function provinsi(): BelongsTo{
        return $this->belongsTo(Provinsi::class);
    }

    public function kecamatans(): HasMany{
        return $this->hasMany(Kecamatan::class);
    }
}