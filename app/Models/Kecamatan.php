<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $table = 'kecamatans';

    protected $fillable = [
        'kabupaten_kota_id',
        'nama_kecamatan',
    ];

    public function kabupatenKota(): BelongsTo{
        return $this->belongsTo(KabupatenKota::class);
    }

    public function kelurahanDesas(): HasMany{
        return $this->hasMany(KelurahanDesa::class);
    }
}