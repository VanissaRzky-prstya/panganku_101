@extends('frontend.layouts.app')
@section('title', 'Tambah Alamat - Panganku')
@section('content')
<section class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-6 py-10">
        <div class="mb-8">
            @include('frontend.components.breadcrumb',[
                'breadcrumbs' => [
                    [
                        'label' => 'Keranjang',
                        'url' => route('keranjang.index')
                    ],[
                        'label' => 'Tambah Alamat',
                        'url' => null
                    ]
                ]
            ])
        </div>
        <div class="mb-8">
            <p class="text-xs uppercase tracking-[0.3em] text-green-600">Alamat Pengiriman</p>
            <h1 class="mt-3 text-3xl md:text-4xl font-bold text-black">Tambahkan Alamat</h1>
            <p class="mt-2 text-sm text-gray-500">Isi alamat yang akan digunakan untuk mengantar pesanan Anda</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8">
            <form action="{{ route('alamat.store') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
                        <p class="font-semibold text-red-600 text-sm">Periksa kembali data berikut:</p>
                        <ul class="mt-2 text-sm text-red-500 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="mb-5">
                    <label for="nama_penerima" class="block text-sm font-semibold text-black mb-2">Nama Penerima</label>
                    <input type="text" id="nama_penerima" name="nama_penerima" value="{{ old('nama_penerima', auth()->user()->name) }}" placeholder="Masukkan Nama"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm text-black outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition"
                    >
                    @error('nama_penerima')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="nomor_telepon" class="block text-sm font-semibold text-black mb-2">Nomor HP</label>
                    <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', auth()->user()->phone) }}" placeholder="Masukkan Nomor"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm text-black outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition"
                    >
                    @error('nomor_telepon')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="provinsi" class="block text-sm font-semibold text-black mb-2">Provinsi</label>
                    <select id="provinsi" name="provinsi" class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm text-gray-700 outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                        <option value="">Pilih Provinsi</option>
                            @foreach ($provinsis as $provinsi)
                                <option value="{{ $provinsi->kode_provinsi }}" {{ old('provinsi') == $provinsi-> kode_provinsi ? 'selected' : '' }}>
                                    {{ $provinsi->nama_provinsi }}
                                </option>
                            @endforeach
                    </select>
                    @error('provinsi')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="kabupaten_kota" class="block text-sm font-semibold text-black mb-2">Kabupaten/Kota</label>
                    <select id="kabupaten_kota" name="kabupaten_kota" disabled class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm text-gray-700 outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                        <option value="">Pilih Kabupaten/Kota</option>
                    </select>
                    @error('kabupaten_kota')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="kecamatan" class="block text-sm font-semibold text-black mb-2">Kecamatan</label>
                    <select id="kecamatan" name="kecamatan" disabled class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm text-gray-700 outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                        <option value="">Pilih Kecamatan</option>
                    </select>
                    @error('kecamatan')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="kelurahan_desa" class="block text-sm font-semibold text-black mb-2">Desa/Kelurahan</label>
                    <select id="kelurahan_desa" name="kelurahan_desa" disabled class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm text-gray-700 outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                        <option value="">Pilih Desa/Kelurahan</option>
                    </select>
                    @error('kelurahan_desa')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="alamat_lengkap" class="block text-sm font-semibold text-black mb-2">Alamat Lengkap</label>
                    <textarea id="alamat_lengkap" name="alamat_lengkap" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm text-black placeholder-gray-400 outline-none resize-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                        {{ trim(old('alamat_lengkap', '')) }}
                    </textarea>
                    @error('alamat_lengkap')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                <input type="hidden" name="place_id" id="place_id" value="{{ old('place_id') }}">
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <a href="{{ route('keranjang.index') }}"
                    class="flex-1 text-center px-5 py-3 border border-gray-300 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition"
                    >
                        Kembali
                    </a>
                    <button type="submit" class="flex-1 px-5 py-3 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition">
                        Simpan Alamat
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    const provinsiSelect = document.getElementById('provinsi');
    const kabupatenKotaSelect = document.getElementById('kabupaten_kota');
    const kecamatanSelect = document.getElementById('kecamatan');
    const kelurahanDesaSelect = document.getElementById('kelurahan_desa');

    provinsiSelect.addEventListener('change', function (){
        const kodeProvinsi = this.value;
        kabupatenKotaSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        kelurahanDesaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

        kecamatanSelect.disabled = true;
        kelurahanDesaSelect.disabled = true;

        if (!kodeProvinsi){
            kabupatenKotaSelect.disabled = true;
            return;
        }
        fetch(`/alamat/kabupaten-kota/${kodeProvinsi}`).then(response => response.json()).then(data => {
            data.forEach(kabupaten => {
                const option = document.createElement('option');
                option.value = kabupaten.kode_kabupaten_kota;
                option.textContent = kabupaten.nama_kabupaten_kota;
                kabupatenKotaSelect.appendChild(option);
            });
            kabupatenKotaSelect.disabled = false;
        })
        .catch(error => {
            console.error('Gagal mengambil data Kabupaten/Kota:', error);
        });
    });
    
    kabupatenKotaSelect.addEventListener('change', function (){
        const kodeKabupatenKota = this.value;
        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        kelurahanDesaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

        kelurahanDesaSelect.disabled = true;

        if (!kodeKabupatenKota){
            kecamatanSelect.disabled = true;
            return;
        }
        fetch(`/alamat/kecamatan/${kodeKabupatenKota}`).then(response => response.json()).then(data => {
            data.forEach(kecamatan => {
                const option = document.createElement('option');
                option.value = kecamatan.kode_kecamatan;
                option.textContent = kecamatan.nama_kecamatan;
                kecamatanSelect.appendChild(option);
            });
            kecamatanSelect.disabled = false;
        })
        .catch(error => {
            console.error('Gagal mengambil data Kecamatan:', error);
        });
    });

    kecamatanSelect.addEventListener('change', function (){
        const kodeKecamatan = this.value;
        kelurahanDesaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

        if (!kodeKecamatan){
            kelurahanDesaSelect.disabled = true;
            return;
        }
        fetch(`/alamat/kelurahan-desa/${kodeKecamatan}`).then(response => response.json()).then(data => {
            data.forEach(kelurahan => {
                const option = document.createElement('option');
                option.value = kelurahan.kode_kelurahan_desa;
                option.textContent = kelurahan.nama_kelurahan_desa;
                kelurahanDesaSelect.appendChild(option);
            });
            kelurahanDesaSelect.disabled = false;
        })
        .catch(error => {
            console.error(
                'Gagal mengambil data Desa/Kelurahan:',
                error
            );
        });
    });
</script>
@endsection
