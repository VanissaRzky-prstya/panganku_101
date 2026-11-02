<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request){
        $kategori = Kategori::with('subkategoris')->latest()->get();
        $produkQuery = Produk::with([
            'subkategori.kategori',
            'promoAktif'
        ])->latest();

        if ($request->filled('kategori')){
            $produkQuery->whereHas('subkategori', function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori);
            });
        }
        if ($request->filled('subkategori')){
            $produkQuery->where('subkategori_id', $request->subkategori);
        }
        if ($request->filled('search')){
            $produkQuery->where('nama_produk', 'like', '%' . $request->search . '%');
        }
        $produk = $produkQuery->paginate(30)->withQueryString();
        $saranPencarian = null;
        if ($request->filled('search') && $produk->total() === 0){
            $kataPencarian = strtolower(trim($request->search));
            $namaProduk = Produk::pluck('nama_produk');
            $skorTerbaik = PHP_INT_MAX;
            $saranTerbaik = null;
    
            foreach ($namaProduk as $nama){
                $kataProdukArray = preg_split('/\s+/',strtolower(trim($nama)));
                foreach ($kataProdukArray as $kataProduk){
                    $kataProduk = preg_replace('/[^a-z0-9]/','',$kataProduk);
                    if ($kataProduk === ''){
                        continue;
                    }
                    $jarak = levenshtein($kataPencarian,$kataProduk);
                    if ($jarak < $skorTerbaik){
                        $skorTerbaik = $jarak;
                        $saranTerbaik = $kataProduk;
                    }
                }
            }
            if ($skorTerbaik <= 2){
                $saranPencarian = $saranTerbaik;
            }
        }
        $kategoriAktif = $request->kategori;
        $subkategoriAktif = $request->subkategori;
        $search = $request->search;
        return view('frontend.produk.index', compact(
            'produk',
            'kategori',
            'kategoriAktif',
            'subkategoriAktif',
            'search',
            'saranPencarian'
        ));
    }

    public function detail($slug){
        $produk = Produk::with([
            'subkategori.kategori',
            'promoAktif'
        ])->where('slug', $slug)->firstOrFail();
        $produkSerupa = Produk::with([
            'subkategori.kategori',
            'promoAktif'
        ])->where('subkategori_id', $produk->subkategori_id)->where('id', '!=', $produk->id)->latest()->paginate(6)->withQueryString();
        return view('frontend.produk.detail', compact(
            'produk',
            'produkSerupa'
        ));
    }
}