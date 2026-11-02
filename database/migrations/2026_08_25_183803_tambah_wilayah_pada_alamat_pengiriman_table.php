<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alamat_pengiriman', function (Blueprint $table){
            $table->string('provinsi')->after('nomor_telepon');
            $table->string('kabupaten_kota')->after('provinsi');
            $table->string('kecamatan')->after('kabupaten_kota');
            $table->string('kelurahan_desa')->after('kecamatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alamat_pengiriman', function (Blueprint $table){
            $table->dropColumn([
                'provinsi',
                'kabupaten_kota',
                'kecamatan',
                'kelurahan_desa',
            ]);
        });
    }
};