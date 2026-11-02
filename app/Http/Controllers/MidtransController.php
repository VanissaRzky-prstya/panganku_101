<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Produk;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function notification(Request $request){
        try {
            app(\App\Services\MidtransService::class);
            $notification = new Notification();
            DB::transaction(function () use ($notification){
                $order = Order::where(
                    'kode_pesanan',
                    $notification->order_id
                )->lockForUpdate()->first();
                if (! $order){
                    throw new \RuntimeException('Pesanan tidak ditemukan');
                }
                $transactionStatus = $notification->transaction_status;
                $fraudStatus = $notification->fraud_status ?? null;
                if($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')){
                    if ($order->status_pembayaran !== 'paid'){
                        $items = $order->items;
                        foreach ($items as $item){
                            $produk = Produk::where(
                                'id',
                                $item->produk_id
                            )->lockForUpdate()->first();
                            if (! $produk){
                                throw new \RuntimeException("Produk ID {$item->produk_id} tidak ditemukan");
                            }
                            if ($produk->stok < $item->qty){
                                throw new \RuntimeException("Stok produk {$produk->nama_produk} tidak mencukupi");
                            }
                            $produk->decrement('stok', $item->qty);
                        }
                        $order->status_pembayaran = 'paid';
                        $order->status_pesanan = 'processing';
                        $order->paid_at = $order->paid_at ?? now();
                        $order->save();
                    }
                } elseif ($transactionStatus === 'pending'){
                    if ($order->status_pembayaran !== 'paid'){
                        $order->update([
                            'status_pembayaran' => 'pending',
                        ]);
                    }
                }elseif ($transactionStatus === 'expire') {
                    if ($order->status_pembayaran !== 'paid'){
                        $order->update([
                            'status_pembayaran' => 'expired',
                        ]);
                    }
                } elseif (in_array($transactionStatus, ['deny', 'cancel'], true)){
                    if ($order->status_pembayaran !== 'paid'){
                        $order->update([
                            'status_pembayaran' => 'failed',
                        ]);
                    }
                }
            });
            return response()->json([
                'message' => 'Notifikasi diterima',
            ]);
        } catch (\Throwable $e) {
            Log::error('Midtrans notification gagal diproses',[
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Notifikasi gagal',
            ], 500);
        }
    }
}
