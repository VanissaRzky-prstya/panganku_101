<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelurahanDesa extends Model
{
    protected $table = 'kelurahan_desas';

    protected $fillable = [
        'kecamatan_id',
        'nama_kelurahan_desa',
    ];

    public function kecamatan(): BelongsTo{
        return $this->belongsTo(Kecamatan::class);
    }
}