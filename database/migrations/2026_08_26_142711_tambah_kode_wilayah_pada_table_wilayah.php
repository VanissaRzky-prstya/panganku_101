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
        Schema::table('provinsis', function (Blueprint $table){
            $table->string('kode_provinsi', 10)->unique()->after('id');
        });
        Schema::table('kabupaten_kotas', function (Blueprint $table){
            $table->string('kode_kabupaten_kota', 20)->unique()->after('provinsi_id');
        });

        Schema::table('kecamatans', function (Blueprint $table){
            $table->string('kode_kecamatan', 20)->unique()->after('kabupaten_kota_id');
        });

        Schema::table('kelurahan_desas', function (Blueprint $table){
            $table->string('kode_kelurahan_desa', 30)->unique()->after('kecamatan_id');
            $table->string('tipe_wilayah', 30)->nullable()->after('nama_kelurahan_desa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelurahan_desas', function (Blueprint $table){
            $table->dropUnique(['kode_kelurahan_desa']);
            $table->dropColumn([
                'kode_kelurahan_desa',
                'tipe_wilayah',
            ]);
        });

        Schema::table('kecamatans', function (Blueprint $table){
            $table->dropUnique(['kode_kecamatan']);
            $table->dropColumn('kode_kecamatan');
        });

        Schema::table('kabupaten_kotas', function (Blueprint $table){
            $table->dropUnique(['kode_kabupaten_kota']);
            $table->dropColumn('kode_kabupaten_kota');
        });

        Schema::table('provinsis', function (Blueprint $table){
            $table->dropUnique(['kode_provinsi']);
            $table->dropColumn('kode_provinsi');
        });
    }
};