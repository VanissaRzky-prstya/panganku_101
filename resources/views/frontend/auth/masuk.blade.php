@extends('frontend.layouts.app')
@section('title', 'Masuk - Panganku')
@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center px-4 py-5">
    <div class="w-full bg-green-800 max-w-md rounded-2xl shadow-lg p-4">
        <img src="{{ asset('images/LOGO.png') }}" alt="PANGANKU" class="h-14 w-auto mx-auto object-contain mb-3">
    <div class="w-full bg-white rounded-xl shadow p-6">
        <h1 class="text-xl font-bold text-center text-gray-800">Masuk</h1>
        <p class="text-center text-gray-500 text-xs mt-1 mb-5">Masuk untuk mulai berbelanja di Panganku</p>
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 px-3 py-2 rounded-lg text-xs">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="mb-4 bg-green-100 text-green-700 px-3 py-2 rounded-lg text-xs">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('masuk.proses') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                autocomplete="tel" inputmode="numeric" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Masukkan Nomor HP">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                    autocomplete="current-password" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-12 text-gray-900 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Masukkan Kata Sandi">
                    <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-gray-600" aria-label="Tampilkan password">
                        <svg class="mata-tutup w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.5 10.5a2.12 2.12 0 0 0 3 3M6.7 6.7C4.3 8.1 2.8 10 2 12c1.2 3.5 4.7 7 10 7 1.8 0 3.4-.4 4.8-1.1M9.9 5.2C10.6 5.1 11.3 5 12 5c5.3 0 8.8 3.5 10 7-.5 1.5-1.4 2.9-2.6 4.1"/>
                        </svg>
                        <svg class="mata-buka hidden w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                <div class="text-right mt-2">
                    <a href="{{ route('lupa.password') }}" class="text-sm text-green-600 hover:underline">Lupa kata sandi?</a>
                </div>
            </div>
            <button type="submit" class="w-full bg-green-500 text-white py-2.5 rounded-lg font-semibold hover:bg-green-600 transition">Masuk</button>
        </form>
        <p class="text-center text-sm text-gray-600 mt-6">
            Belum punya akun?
            <a href="{{ route('daftar') }}" class="text-green-600 font-semibold hover:underline">
                Daftar
            </a>
        </p>
    </div>
</div>
<script>
function togglePassword(inputId, button){
    const input = document.getElementById(inputId);
    const mataTutup = button.querySelector('.mata-tutup');
    const mataBuka = button.querySelector('.mata-buka');
    if (input.type === 'password'){
        input.type = 'text';
        mataTutup.classList.add('hidden');
        mataBuka.classList.remove('hidden');
    }else{
        input.type = 'password';
        mataBuka.classList.add('hidden');
        mataTutup.classList.remove('hidden');
    }
}
</script>
@endsection
