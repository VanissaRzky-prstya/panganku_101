<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PenjualanExport;
use App\Models\OrderItem;
use App\Exports\ProdukTerlarisExport;
use App\Exports\ProdukKurangLarisExport;
use App\Exports\PendapatanProdukExport;
use App\Models\Produk;
use App\Exports\StokProdukExport;

class ExportController extends Controller
{
    public function penjualanExcel(){
        return Excel::download(new PenjualanExport, 'laporan-penjualan.xlsx');
    }

    public function penjualanPdf(){
        $orders = Order::with('user')->latest()->get();
        $pdf = Pdf::loadView('exports.penjualan', compact('orders'));
        return $pdf->download('laporan-penjualan.pdf');
    }

    public function produkTerlarisExcel(){
        return Excel::download(
            new ProdukTerlarisExport,
            'laporan-produk-terlaris.xlsx'
        );
    }

    public function produkTerlarisPdf(){
        $produks = OrderItem::with('produk.subkategori')->selectRaw('produk_id, SUM(qty) as total_terjual, SUM(subtotal) as total_pendapatan')
            ->groupBy('produk_id')->orderByDesc('total_terjual')->get();
        $pdf = Pdf::loadView('exports.produk_terlaris', compact('produks'));
            return $pdf->download('laporan-produk-terlaris.pdf');
    }

    public function produkKurangLarisExcel(){
        return Excel::download(
            new ProdukKurangLarisExport,
            'laporan-produk-kurang-laris.xlsx'
        );
    }

    public function produkKurangLarisPdf(){
        $produks = OrderItem::with('produk.subkategori')->selectRaw('produk_id, SUM(qty) as total_terjual, SUM(subtotal) as total_pendapatan')
            ->groupBy('produk_id')->orderBy('total_terjual')->get();
        $pdf = Pdf::loadView('exports.produk_kurang_laris', compact('produks'));
        return $pdf->download('laporan-produk-kurang-laris.pdf');
    }

    public function pendapatanProdukExcel(Request $request){
        $filters = $request->input('filters', []);
        return Excel::download(
            new PendapatanProdukExport($filters),
            'laporan-pendapatan-produk.xlsx'
        );
    }

    public function pendapatanProdukPdf(){
        $produks = OrderItem::with('produk.subkategori')->selectRaw('produk_id, SUM(qty) as total_terjual, SUM(subtotal) as total_pendapatan')
            ->groupBy('produk_id')->orderByDesc('total_pendapatan')->get();
        $pdf = Pdf::loadView('exports.pendapatan_produk', compact('produks'));
        return $pdf->download('laporan-pendapatan-produk.pdf');
    }

    
public function stokProdukExcel(Request $request)
{
    $status = $request->query('status');

    return Excel::download(
        new StokProdukExport($status),
        'laporan-stok-produk.xlsx'
    );
}

public function stokProdukPdf(Request $request)
{
    $query = Produk::with('subkategori');

    if ($request->query('status') === 'habis') {
        $query->where('stok', 0);
    } elseif ($request->query('status') === 'hampir_habis') {
        $query->where('stok', '>', 0)
              ->where('stok', '<=', 22);
    } elseif ($request->query('status') === 'aman') {
        $query->where('stok', '>', 22);
    }

    $produks = $query->get();

    $pdf = Pdf::loadView('exports.stok_produk', compact('produks'));

    return $pdf->download('laporan-stok-produk.pdf');
}

}
