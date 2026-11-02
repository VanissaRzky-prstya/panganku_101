<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Promo;

class BerandaController extends Controller
{
    public function index(){
        $kategori = Kategori::latest()->take(7)->get();
        $produk = Produk::latest()->get();
        $promo = Promo::with('produk')->where('is_active', true)->whereDate('tanggal_mulai', '<=', today())
            ->whereDate('tanggal_berakhir', '>=', today())->get();
        return view('frontend.beranda.index', compact(
            'kategori', 
            'promo',
            'produk'
        ));
    }
}
