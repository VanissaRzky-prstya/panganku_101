@extends('frontend.layouts.app')
@section('content')
<div class="bg-gray-50 min-h-screen">
    <section class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-6 pt-5 pb-8s">
            <div class="bg-white border border-gray-300 rounded-full px-6 py-3 shadow-sm">
                <div class="flex items-center gap-5 text-base">
                    <a href="{{ route('beranda') }}" class="text-gray-500 hover:text-black transition">Beranda</a>
                    <span class="text-green-500 font-semibold">›</span>
                    <span class="text-gray-600">Akun Saya</span>
                </div>
            </div>
            <div class="text-center mt-8">
                <h1 class="text-3xl font-bold text-black">Akun Saya</h1>
                <p class="text-sm text-gray-500 mt-2">Kelola informasi akun dan kebutuhan belanja Anda di PANGANKU</p>
            </div>
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <aside>
                <div class="space-y-2">
                    <button type="button" class="w-full text-left px-5 py-3 bg-green-700 text-white rounded-lg text-sm font-medium">
                        Profil Akun
                    </button>
                    <button type="button" class="w-full text-left px-5 py-3 bg-white border border-gray-200 rounded-lg text-sm hover:bg-gray-100 transition">
                        Pesanan Saya
                    </button>
                    <button type="button" class="w-full text-left px-5 py-3 bg-white border border-gray-200 rounded-lg text-sm hover:bg-gray-100 transition">
                        Alamat Pengiriman
                    </button>
                    <button type="button" class="w-full text-left px-5 py-3 bg-white border border-gray-200 rounded-lg text-sm hover:bg-gray-100 transition">
                        Keamanan Akun
                    </button>
                    <button type="button" onclick="bukaModalKeluar()" class="w-full text-left px-5 py-3 bg-white border border-gray-200 rounded-lg text-sm text-red-500 hover:bg-red-50 transition">
                        <span class="material-symbols-outlined icon-logout">logout</span>
                        Keluar
                    </button>
                </div>
            </aside>
            <main class="md:col-span-3">
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <button type="button" onclick="bukaModalFoto()"
                                class="relative w-16 h-16 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden hover:ring-2 hover:ring-green-500 transition"
                                title="Ubah foto"
                            >
                                @if(auth()->user()->photo)
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="w-full h-full object-cover" alt="foto-profil">
                                @else
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined icon-contacts_product text-2xl text-green-500">contacts_product</span>
                                    </div>
                                @endif
                            </button>
                            <div>
                                <h2 class="font-bold text-lg text-black">{{ auth()->user()->name }}</h2>
                                <p class="text-sm text-gray-500">Pelanggan Panganku</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <button type="button" onclick="aktifkanEdit()" id="tombolUbah"
                                class="px-5 py-2 border border-green-700 text-black rounded-full text-sm hover:bg-green-700 transition"
                            >
                                Ubah
                            </button>
                            <button type="submit" form="formAkun" id="tombolSimpan"
                                class="hidden px-5 py-2 bg-green-500 text-white rounded-full text-sm hover:bg-green-700 transition"
                            >
                                Simpan
                            </button>
                        </div>
                    </div>
                    <hr class="my-6">
                    <form action="{{ route('akun.update') }}" method="POST" id="formAkun">
                        @csrf
                        @method('PUT')
                        <h3 class="font-bold text-lg text-black mb-5">Informasi Pribadi</h3>
                        <div class="grid grid-cols-1 gap-5">
                            <div>
                                <label class="block text-xs text-gray-500 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}"
                                disabled class="input-akun w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-sm outline-none"
                                >
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-2">Email</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}"
                                disabled class="input-akun w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-sm outline-none"
                                >
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-2">Nomor Telepon</label>
                                <input type="text" name="phone" value="{{ auth()->user()->phone }}"
                                disabled class="input-akun w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-sm outline-none"
                                >
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </section>
</div>
<div id="modalFoto" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-bold text-lg text-black">Foto Profil</h2>
            <button type="button" onclick="tutupModalFoto()" class="text-gray-500 text-xl hover:text-black transition">
                ×
            </button>
        </div>
        <div class="flex justify-center mb-6">
            <div class="w-40 h-40 rounded-full bg-gray-100 border border-gray-300 overflow-hidden flex items-center justify-center">
                @if(auth()->user()->photo)
                    <img id="previewFoto" src="{{ asset('storage/' . auth()->user()->photo) }}" class="w-full h-full object-cover" alt="foto-profil">
                @else
                    <img id="previewFoto" class="hidden w-full h-full object-cover" alt="Preview Foto">
                    <span id="previewKosong" class="text-5xl text-gray-400">+</span>
                @endif
            </div>
        </div>
        <form action="{{ route('akun.foto.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="photo" id="inputFoto" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewFotoBaru(event)">
            <button type="button" onclick="document.getElementById('inputFoto').click()"
            class="w-full px-5 py-3 border border-green-500 text-black rounded-full text-sm hover:bg-green-50 transition">
                Pilih Foto
            </button>
            <button type="submit" id="tombolSimpanFoto"
            class="w-full mt-3 px-5 py-3 bg-green-600 text-white rounded-full text-sm hover:bg-green-700 transition">
                Simpan
            </button>
        </form>
    </div>
</div>
<div id="modalKeluar" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl w-full max-w-sm p-6 text-center">
        <h2 class="font-bold text-lg">Keluar dari Akun</h2>
        <p class="text-sm text-gray-500 mt-2">Yakin ingin keluar dari akun ini?</p>
        <div class="flex gap-3 mt-6">
            <button type="button" onclick="tutupModalKeluar()" class="flex-1 px-4 py-3 border border-gray-300 rounded-full text-sm">Tidak</button>
            <form action="{{ route('keluar') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full px-4 py-3 bg-black text-white rounded-full text-sm">
                    Ya
                </button>
            </form>
        </div>
    </div>
</div>
@if(session('success'))
    <div id="notifikasi"
    class="fixed top-6 left-1/2 -translate-x-1/2 z-[60] bg-green-500 text-white px-6 py-3 rounded-full shadow-lg text-sm font-medium">
        ✓ {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            const notif = document.getElementById('notifikasi');
            if (notif) {
                notif.remove();
            }
        }, 3000);
    </script>
@endif
<script>
    function aktifkanEdit(){
        const inputs = document.querySelectorAll('.input-akun');
        inputs.forEach(input => {
            if (input.id === 'password'){
                return;
            }
            input.disabled = false;
            input.classList.remove(
                'bg-gray-50',
                'bg-gray-100',
                'text-gray-500',
                'cursor-not-allowed'
            );
            input.classList.add(
                'bg-white',
                'border-black',
                'text-black'
            );
        });
        document.getElementById('tombolUbah').classList.add('hidden');
        document.getElementById('tombolSimpan').classList.remove('hidden');
    }
    function bukaModalFoto(){
        document.getElementById('modalFoto').classList.remove('hidden');
    }
    function tutupModalFoto(){
        document.getElementById('modalFoto').classList.add('hidden');
    }
    function previewFotoBaru(event){
        const file = event.target.files[0];
        if(!file){
            return;
        }
        if (!file.type.startsWith('image/')){
            alert('Silakan pilih gambar');
            event.target.value = '';
            return;
        }
        const preview = document.getElementById('previewFoto');
        const kosong = document.getElementById('previewKosong');
        const reader = new FileReader();
        reader.onload = function(e){
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (kosong){
                kosong.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }

    function bukaModalKeluar(){
        document.getElementById('modalKeluar').classList.remove('hidden');
    }

    function tutupModalKeluar(){
        document.getElementById('modalKeluar').classList.add('hidden');
    }
</script>
@endsection