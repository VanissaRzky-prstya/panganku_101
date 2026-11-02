<?php

namespace App\Http\Controllers;

use App\Models\AlamatPengiriman;
use App\Models\Provinsi;
use App\Models\KabupatenKota;
use App\Models\Kecamatan;
use App\Models\KelurahanDesa;
use Illuminate\Http\Request;

class AlamatPengirimanController extends Controller
{
    public function create(){
        $provinsis = Provinsi::orderBy('nama_provinsi')->get();
        return view('frontend.alamat.create', compact('provinsis'));
    }

    public function kabupatenKota(string $kodeProvinsi){
        $provinsi = Provinsi::where('kode_provinsi', $kodeProvinsi)->firstOrFail();
        $kabupatenKotas = KabupatenKota::where('provinsi_id', $provinsi->id)->orderBy('nama_kabupaten_kota')->get(['kode_kabupaten_kota', 'nama_kabupaten_kota']);
        return response()->json($kabupatenKotas);
    }

    public function kecamatan(string $kodeKabupatenKota){
        $kabupatenKota = KabupatenKota::where(
            'kode_kabupaten_kota',
            $kodeKabupatenKota
        )
        ->firstOrFail();
        $kecamatans = Kecamatan::where(
            'kabupaten_kota_id',
            $kabupatenKota->id
        )
        ->orderBy('nama_kecamatan')->get(['kode_kecamatan', 'nama_kecamatan']);
        return response()->json($kecamatans);
    }

    public function kelurahanDesa(string $kodeKecamatan){
        $kecamatan = Kecamatan::where(
            'kode_kecamatan',
            $kodeKecamatan
        )->firstOrFail();
        $kelurahanDesas = KelurahanDesa::where(
            'kecamatan_id',
            $kecamatan->id
        )
        ->orderBy('nama_kelurahan_desa')->get(['kode_kelurahan_desa', 'nama_kelurahan_desa']);
        return response()->json($kelurahanDesas);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'nama_penerima' => ['required', 'string', 'max:100'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'provinsi' => ['required', 'string', 'max:255'],
            'kabupaten_kota' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'kelurahan_desa' => ['required', 'string', 'max:255'],
            'alamat_lengkap' => ['required', 'string', 'max:1000'],
            'patokan' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'place_id' => ['nullable', 'string', 'max:255'],
        ],[
            'nama_penerima.required' => 'Nama penerima wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'alamat_lengkap.required' => 'Alamat lengkap wajib diisi.',
        ]);

        $validated['provinsi'] = Provinsi::where('kode_provinsi',$validated['provinsi'])->value('nama_provinsi');
        $validated['kabupaten_kota'] = KabupatenKota::where('kode_kabupaten_kota',$validated['kabupaten_kota'])->value('nama_kabupaten_kota');
        $validated['kecamatan'] = Kecamatan::where('kode_kecamatan',$validated['kecamatan'])->value('nama_kecamatan');
        $validated['kelurahan_desa'] = KelurahanDesa::where('kode_kelurahan_desa',$validated['kelurahan_desa'])->value('nama_kelurahan_desa');
        
        $user = auth()->user();
        $jumlahAlamat = $user->alamatPengiriman()->count();
        $validated['user_id'] = $user->id;
        $validated['is_utama'] = $jumlahAlamat === 0;
        $alamat = AlamatPengiriman::create($validated);
        return redirect()->route('keranjang.index')->with('success', 'Alamat pengiriman berhasil ditambahkan.');
    }
}