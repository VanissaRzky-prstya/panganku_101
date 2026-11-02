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
        Schema::create('order_items', function (Blueprint $table){
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produks')->cascadeOnDelete();
            $table->unsignedBigInteger('harga_asli');
            $table->enum('tipe_potongan', [
                'persentase',
                'nominal',
            ])->nullable();
            $table->decimal('besar_potongan', 10, 2)->default(0);
            $table->unsignedBigInteger('harga_setelah_diskon');
            $table->unsignedInteger('qty');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
