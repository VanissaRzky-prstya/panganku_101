<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Produk;

class PromoController extends Controller
{
    public function index(){
        $promo = Promo::with([
            'produk.subkategori.kategori',
            'produk.promoAktif'
        ])->where('is_active', true)->whereDate('tanggal_mulai', '<=', today())->whereDate('tanggal_berakhir', '>=', today())->latest()->paginate(12);
        return view('frontend.promo.index', compact('promo'));
    }

    public function detail($id){
        $promo = Promo::with([
            'produk.subkategori.kategori',
            'produk.promoAktif'
        ])->where('id', $id)->where('is_active', true)->whereDate('tanggal_mulai', '<=', today())->whereDate('tanggal_berakhir', '>=', today())->firstOrFail();
        $produkSerupa = Produk::with([
            'subkategori.kategori',
            'promoAktif'
        ])->where('subkategori_id', $promo->produk->subkategori_id)->where('id', '!=', $promo->produk->id)->latest()->paginate(6)->withQueryString();
        return view('frontend.promo.detail_promo', compact(
            'promo',
            'produkSerupa'
        ));
    }
}