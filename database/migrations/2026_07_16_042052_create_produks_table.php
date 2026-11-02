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
        Schema::create('produks', function (Blueprint $table){
            $table->id();
            $table->foreignId('subkategori_id')->constrained('subkategoris')->cascadeOnDelete();
            $table->string('nama_produk');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('foto_produk');
            $table->unsignedBigInteger('harga');
            $table->integer('stok');
            $table->decimal('berat', 8, 2);
            $table->string('satuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
