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
        Schema::create('orders', function (Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kode_pesanan')->unique();
            $table->unsignedBigInteger('total_harga')->default(0);
            $table->unsignedBigInteger('ongkir')->default(0);
            $table->unsignedBigInteger('total_bayar')->default(0);
            $table->enum('payment_type', [
                'bank',
                'qris',
                'ewallet',
            ])->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference', 100)->nullable();
            $table->enum('status_pembayaran', [
                'pending',
                'paid',
                'expired',
                'failed',
                'refunded',
            ])->default('pending');
            $table->enum('status_pesanan', [
                'pending',
                'processing',
                'packed',
                'shipping',
                'completed',
                'cancelled',
            ])->default('pending');
            $table->date('tanggal_pengiriman');
            $table->unsignedTinyInteger('delivery_slot')->nullable();
            $table->string('jam_pengiriman')->nullable();
            $table->text('catatan');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
