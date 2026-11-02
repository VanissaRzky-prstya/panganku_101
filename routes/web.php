<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\AlamatPengirimanController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MidtransController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [BerandaController::class, 'index'])->name('beranda');

Route::get('/daftar', [AuthController::class, 'daftar'])->name('daftar');
Route::post('/daftar', [AuthController::class, 'prosesDaftar'])->name('daftar.proses');

Route::get('/masuk', [AuthController::class, 'masuk'])->name('masuk');
Route::post('/masuk', [AuthController::class, 'prosesMasuk'])->name('masuk.proses');

Route::get('/lupa-password', [AuthController::class, 'lupaPassword'])->name('lupa.password');
Route::post('/lupa-password', [AuthController::class, 'prosesLupaPassword'])->name('lupa.password.proses');

Route::get('/kategori/{filename}', function ($filename){
    $path = 'kategori/' . $filename;
    if (!Storage::disk('local')->exists($path)){
        abort(404);
    }
    return response()->file(Storage::disk('local')->path($path));
})->name('kategori.foto');

Route::get('/produk/{filename}', function ($filename){
    $path = 'produk/' . $filename;
    if (!Storage::disk('local')->exists($path)){
        abort(404);
    }
    return response()->file(Storage::disk('local')->path($path));
})->name('produk.foto');

Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
Route::get('/produk/detail/{slug}', [ProdukController::class, 'detail'])->name('produk.detail');

Route::get('/promo', [PromoController::class, 'index'])->name('promo.index');
Route::get('/promo/{id}', [PromoController::class, 'detail'])->name('promo.detail_promo');


Route::middleware(['auth'])->group(function (){
    Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
    Route::put('/akun', [AkunController::class, 'update'])->name('akun.update');
    Route::post('/akun/foto', [AkunController::class, 'updateFoto'])->name('akun.foto.update');

    Route::get('/alamat/tambah', [AlamatPengirimanController::class, 'create'])->name('alamat.create');
    Route::post('/alamat', [AlamatPengirimanController::class, 'store'])->name('alamat.store');
    Route::get('/alamat/kabupaten-kota/{kodeProvinsi}', [AlamatPengirimanController::class, 'kabupatenKota'])->name('alamat.kabupaten-kota');
    Route::get('/alamat/kecamatan/{kodeKabupatenKota}', [AlamatPengirimanController::class, 'kecamatan'])->name('alamat.kecamatan');
    Route::get('/alamat/kelurahan-desa/{kodeKecamatan}', [AlamatPengirimanController::class, 'kelurahanDesa'])->name('alamat.kelurahan-desa');

    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::post('/keranjang/lebih', [KeranjangController::class, 'lebih'])->name('keranjang.lebih');
    Route::post('/keranjang/jumlah', [KeranjangController::class, 'jumlah'])->name('keranjang.jumlah');
    Route::post('/keranjang/kurang', [KeranjangController::class, 'kurang'])->name('keranjang.kurang');
    Route::delete('/keranjang/hapus', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::delete('/keranjang/kosongkan', [KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');
    Route::get('/keranjang/data', [KeranjangController::class, 'data'])->name('keranjang.data');
    Route::post('/keranjang/pengiriman', [KeranjangController::class, 'simpanPengiriman'])->name('keranjang.pengiriman');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/proses', [CheckoutController::class, 'proses'])->name('checkout.proses');

    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}', [PesananController::class, 'detail'])->name('pesanan.detail');
});  

Route::post('/keluar', [AuthController::class, 'keluar'])->name('keluar');

Route::prefix('admin/export')->middleware(['auth'])->group(function (){
    Route::get('/penjualan/excel', [ExportController::class, 'penjualanExcel'])->name('export.penjualan.excel');
    Route::get('/penjualan/pdf', [ExportController::class, 'penjualanPdf'])->name('export.penjualan.pdf');

    Route::get('/produk-terlaris/excel',[ExportController::class, 'produkTerlarisExcel'])->name('export.produk.terlaris.excel');
    Route::get('/produk-terlaris/pdf',[ExportController::class, 'produkTerlarisPdf'])->name('export.produk.terlaris.pdf');
    Route::get('/produk-kurang-laris/excel', [ExportController::class, 'produkKurangLarisExcel'])->name('export.produk.kuranglaris.excel');
    Route::get('/produk-kurang-laris/pdf', [ExportController::class, 'produkKurangLarisPdf'])->name('export.produk.kuranglaris.pdf');

    Route::get('/pendapatan-produk/excel', [ExportController::class, 'pendapatanProdukExcel'])->name('export.pendapatan.excel');
    Route::get('/pendapatan-produk/pdf', [ExportController::class, 'pendapatanProdukPdf'])->name('export.pendapatan.pdf');

    Route::get('/stok-produk/excel', [ExportController::class, 'stokProdukExcel'])->name('export.stok.excel');
    Route::get('/stok-produk/pdf', [ExportController::class, 'stokProdukPdf'])->name('export.stok.pdf');
});

Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');
