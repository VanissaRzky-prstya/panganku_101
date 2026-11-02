<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlamatPengiriman extends Model
{
    protected $table = 'alamat_pengiriman';
    
    protected $fillable = [
        'user_id',
        'nama_penerima',
        'nomor_telepon',
        'provinsi',
        'kabupaten_kota',
        'kecamatan',
        'kelurahan_desa',
        'alamat_lengkap',
        'patokan',
        'is_utama',
    ];

    protected $casts = [
        'is_utama' => 'boolean',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}