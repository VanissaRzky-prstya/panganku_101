<?php

namespace App\Http\Controllers;

use App\Models\Order;

class PesananController extends Controller
{
    public function index(){
        $pesanan = Order::where('user_id', auth()->id())->with('items.produk')->latest()->get();
        return view('frontend.pesanan.index', compact('pesanan'));
    }

    public function detail($id){
        $pesanan = Order::where('user_id', auth()->id())->with(['user','alamat','items.produk',])->findOrFail($id);
        return view('frontend.pesanan.detail', compact('pesanan'));
    }
}
