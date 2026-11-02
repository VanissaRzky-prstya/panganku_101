<nav class="sticky top-0 z-50 bg-green-900 text-white">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <img src="{{ asset('images/LOGO.png') }}" alt="PANGANKU" class="h-14 w-auto object-contain">
        <div class="hidden md:flex flex-1 max-w-4xl mx-8">
            <form action="{{ route('produk.index') }}" method="GET" class="w-full flex items-center bg-white rounded-full overflow-hidden">
                <input type="text" name="search" placeholder="Cari produk..." class="flex-1 px-5 py-2 text-gray-800 outline-none">
                <button type="submit" class="px-5 py-2 text-gray-800 hover:bg-gray-100 transition">
                    <span class="material-symbols-outlined icon-search">search</span>
                </button>
            </form>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('keranjang.index') }}" class="hover:text-gray-500 transition mx-1" title="Keranjang">
                    <span class="material-symbols-outlined icon-cart">shopping_basket</span>
                </a>
                <a href="{{ route('pesanan.index') }}" class="navbar-icon hover:text-gray-500 transition mx-2" title="Pesanan">
                    <span class="material-symbols-outlined icon-orders">orders</span>
                </a>
                <div class="relative flex items-center gap-2">
                    <a href="{{ route('akun.index') }}" class="block" title="Akun Saya">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-300 flex items-center justify-center border-2 border-white hover:opacity-90 transition">
                            @if(auth()->user()->photo)
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}"alt="Foto Profil" class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined icon-contacts_product">contacts_product</span>
                            @endif
                        </div>
                    </a> 
                    <button type="button" onclick="toggleDropdownAkun()" id="tombolDropdownAkun" title="Menu Akun" class="p-1.5 rounded-full hover:bg-white/10 transition">
                        <span id="iconPanahAkun" class="material-symbols-outlined icon-arrow_drop_down">arrow_drop_down</span>    
                    </button>
                    <div id="dropdownAkun" class="hidden absolute right-0 top-full mt-3 w-72 bg-white text-gray-800 rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">    
                        <div class="p-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center flex-shrink-0">
                                    @if(auth()->user()->photo)
                                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Foto Profil" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-symbols-outlined icon-contacts_product">contacts_product</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                    @if(auth()->user()->phone)
                                        <p class="text-sm text-gray-500 truncate">{{ auth()->user()->phone }}</p>
                                    @endif
                                    @if(auth()->user()->email)
                                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="py-2">
                            <a href="{{ route('akun.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                                <span class="material-symbols-outlined icon-contacts_product">contacts_product</span>
                                <span class="text-sm">Akun Saya</span>
                            </a>
                            <a href="{{ route('alamat.create') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                                <span class="material-symbols-outlined icon-location_on">location_on</span>
                                <span class="text-sm">Alamat Pengiriman</span>
                            </a>
                        </div>
                        <div class="border-t border-gray-100 p-2">
                            <form action="{{ route('keluar') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-3 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <span class="material-symbols-outlined icon-logout">logout</span>
                                    <span class="text-sm">Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('masuk') }}" class="hover:text-gray-500 transition mx-1" title="Masuk untuk melihat keranjang">
                    <span class="material-symbols-outlined icon-cart">shopping_basket</span>
                </a>
                <a href="{{ route('masuk') }}" class="navbar-icon hover:text-gray-500 transition mx-2" title="Pesanan">
                    <span class="material-symbols-outlined icon-orders">orders</span>
                </a>

                <a href="{{ route('masuk') }}" class="px-4 py-2 border border-white rounded-full hover:bg-white hover:text-black transition">Masuk</a>
                <a href="{{ route('daftar') }}" class="px-4 py-2 border border-white bg-white text-black rounded-full hover:bg-gray-200 transition">Daftar</a>
            @endauth
        </div>
    </div>
</nav>
<script>
    function toggleDropdownAkun(){
        const dropdown = document.getElementById('dropdownAkun');
        const icon = document.getElementById('iconPanahAkun');
        if (!dropdown){
            return;
        }
        dropdown.classList.toggle('hidden');
        if (icon){
            icon.classList.toggle('rotate-180');
        }
    }
    document.addEventListener('click', function(event){
        const dropdown = document.getElementById('dropdownAkun');
        const tombol = document.getElementById('tombolDropdownAkun');
        if (!dropdown || !tombol){
            return;
        }
        if(!dropdown.contains(event.target) && !tombol.contains(event.target)){
            dropdown.classList.add('hidden');
            const icon = document.getElementById('iconPanahAkun');
            if (icon){
                icon.classList.remove('rotate-180');
            }
        }
    });
</script>