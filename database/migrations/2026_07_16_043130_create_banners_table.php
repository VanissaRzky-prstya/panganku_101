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
        Schema::create('banners', function (Blueprint $table){
            $table->id();
            $table->string('judul');
            $table->string('sub_judul')->nullable();
            $table->string('gambar');
            $table->string('tema')->default('Fresh Green');
            $table->string('template')->default('style_1');
            $table->string('warna_awal')->default('#2E7D32');
            $table->string('warna_akhir')->default('#66BB6A');
            $table->string('teks_tombol')->default('Belanja Sekarang');
            $table->string('target')->default('beranda');
            $table->unsignedBigInteger('target_id')->nullable();
            $table->integer('urutan')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
