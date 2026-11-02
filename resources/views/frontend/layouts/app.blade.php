<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panganku')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <link rel="stylesheet" 
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=shopping_basket,search,notifications,orders,location_on,hand_package,sticky_note_2,add_shopping_cart,delete,rocket_launch,package_2,shopping_bag,payments,arrow_drop_down,contacts_product,logout,qr_code_scanner,account_balance,account_balance_wallet,receipt_long,grocery,arrow_back">
</head>
<body data-sudah-login="{{ auth()->check() ? 'true' : 'false' }}">
    @include('frontend.partials.navbar')
    <main>@yield('content')</main>
    <footer class="bg-green-900 text-white">
        <section class="max-w-7xl mx-auto px-6 py-10">
           <div class="text-center mb-9">
                <h2 class="text-2xl md:text-3xl font-bold">
                    Mengapa Belanja di
                    <span class="text-green-400">Panganku?</span>
                </h2>
                <p class="text-green-100 text-sm mt-2">Belanja kebutuhan pangan dengan mudah dan praktis</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div class="text-center px-3">
                    <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-green-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl">grocery</span>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Produk Segar</h3>
                    <p class="text-sm text-green-100 leading-relaxed">
                        Menyediakan berbagai bahan pangan seperti sayur, buah, dan
                        kebutuhan dapur untuk memenuhi kebutuhan sehari-hari.
                    </p>
                </div>
                <div class="text-center px-3">
                    <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-green-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl">payments</span>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Pembayaran Praktis</h3>
                    <p class="text-sm text-green-100 leading-relaxed">
                        Tersedia berbagai pilihan pembayaran sehingga pengguna
                        dapat memilih metode yang sesuai dan nyaman digunakan.
                    </p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto mb-3rounded-full bg-green-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl">shopping_basket</span>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Pilihan Produk Beragam</h3>
                    <p class="text-sm text-green-100 leading-relaxed">
                        Berbagai kebutuhan pangan seperti beras, telur, minyak,
                        tepung, bumbu, daging, ikan, dan produk lainnya tersedia
                        dalam satu tempat.
                    </p>
                </div>
            </div>
        </section>
        <section class="border-t border-green-800">
            <div class="max-w-7xl mx-auto px-6 py-9">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                    <div class="md:col-span-2">
                        <h2 class="text-2xl font-bold mb-4">Tentang <span class="text-green-400">Panganku</span></h2>
                        <p class="text-gray-200 leading-relaxed text-sm md:text-base text-justify">
                            Panganku merupakan aplikasi web penjualan bahan pangan
                            yang memudahkan pengguna dalam mencari dan membeli
                            berbagai kebutuhan sehari-hari. PANGANKU menyediakan
                            beragam produk pangan seperti sayur, buah, beras, telur,
                            daging, ikan, bumbu, minyak, tepung, dan kebutuhan dapur
                            lainnya. Pengguna dapat memilih produk, mengatur jumlah
                            pembelian, menentukan alamat pengiriman, serta melakukan
                            pembayaran melalui metode yang tersedia.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold">Metode Pembayaran</h2>
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                            <div class="bg-white p-2 rounded-md h-12 flex items-center justify-center">
                                <img src="{{ asset('images/pembayaran/bca.jpg') }}" alt="BCA" class="max-w-full max-h-full object-contain">
                            </div>
                            <div class="bg-white p-2 rounded-md h-12 flex items-center justify-center">
                                <img src="{{ asset('images/pembayaran/bni.jpg') }}" alt="BNI" class="max-w-full max-h-full object-contain">
                            </div>
                            <div class="bg-white p-2 rounded-md h-12 flex items-center justify-center">
                                <img src="{{ asset('images/pembayaran/bri.jpg') }}" alt="BRI" class="max-w-full max-h-full object-contain">
                            </div>
                            <div class="bg-white p-2 rounded-md h-12 flex items-center justify-center">
                                <img src="{{ asset('images/pembayaran/mandiri.jpg') }}" alt="Mandiri" class="max-w-full max-h-full object-contain">
                            </div>
                            <div class="bg-white p-2 rounded-md h-12 flex items-center justify-center">
                                <img src="{{ asset('images/pembayaran/gopay.jpg') }}" alt="GoPay" class="max-w-full max-h-full object-contain">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </footer>
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            function ambilKeranjang(){
                return fetch('{{ route('keranjang.data') }}',{
                    method: 'GET', headers:{
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json());
            }
            function sinkronkanHargaKeranjang(data){
                if (!data.success){
                    return;
                }
                const keranjang = data.keranjang || {};
                let adaPerubahanHarga = false;
                document.querySelectorAll('[data-produk-id][data-harga-saat-ini]').forEach(function (element){
                    const produkId = element.dataset.produkId;
                    const dataProduk = keranjang[produkId];
                    if (!dataProduk){
                        return;
                    }
                    const hargaLama = parseFloat(element.dataset.hargaSaatIni) || 0;
                    const hargaBaru = parseFloat(dataProduk.harga_saat_ini) || 0;
                    if (hargaLama !== hargaBaru){
                        adaPerubahanHarga = true;
                        element.dataset.hargaSaatIni = hargaBaru;
                        const hargaSekarang = element.querySelector('.harga-sekarang');
                        if (hargaSekarang){
                            hargaSekarang.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaBaru);
                        }
                        const hargaNormal = element.querySelector('.harga-normal');
                        if (hargaNormal){
                            const hargaNormalProduk = parseFloat(dataProduk.harga_normal) || 0;
                            if (hargaBaru < hargaNormalProduk){
                                hargaNormal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaNormalProduk);
                                hargaNormal.classList.remove('hidden');
                            }else{
                                hargaNormal.textContent = '';
                                hargaNormal.classList.add('hidden');
                            }
                        }
                        const jumlah = parseInt(dataProduk.jumlah) || 0;
                        const subtotal = hargaBaru * jumlah;
                        const subtotalElement = element.querySelector('.subtotal-produk');
                        if (subtotalElement){
                            subtotalElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
                        }
                    }
                });
                let totalHarga = 0;
                Object.keys(keranjang).forEach(function (produkId){
                    const dataProduk = keranjang[produkId];
                    if (!dataProduk){
                        return;
                    }
                    const harga = parseFloat(dataProduk.harga_saat_ini) || 0;
                    const jumlah = parseInt(dataProduk.jumlah) || 0;
                    totalHarga += harga * jumlah;
                });
                const totalFormatted = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga);
                const ringkasanSubtotal = document.getElementById('ringkasan-subtotal');
                const ringkasanTotal = document.getElementById('ringkasan-total');
                if (ringkasanSubtotal){
                    ringkasanSubtotal.textContent = totalFormatted;
                }
                if (ringkasanTotal){
                    ringkasanTotal.textContent = totalFormatted;
                }
                if (adaPerubahanHarga){
                    const notifikasi = document.getElementById('notifikasi-perubahan-harga');
                    if (notifikasi){
                        notifikasi.classList.remove('hidden');
                    }
                }
            }
            function kirimKeranjang(url, produkId){
                return fetch(url,{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({produk_id: produkId})
                })
                .then(response => response.json());
            }
            function perbaruiSemuaTombolProduk(produkId, jumlah){
                const semuaElement = document.querySelectorAll(
                    `[data-produk-id="${produkId}"]`
                );
                semuaElement.forEach(function (element){
                    if (jumlah > 0){
                        if (element.classList.contains('tombol-tambah')){
                            element.outerHTML = `
                            <div class="kontrol-jumlah w-full flex items-center justify-between bg-green-500 text-black rounded-lg overflow-hidden" data-produk-id="${produkId}" data-jumlah="${jumlah}">
                                <button type="button" class="tombol-kurang w-10 py-2 text-lg font-bold hover:bg-green-600">−</button>
                                <input type="text" inputmode="numeric" class="jumlah-produk w-full py-2 text-center font-semibold bg-transparent outline-none" 
                                    value="${jumlah}" data-jumlah-lama="${jumlah}" autocomplete="off">
                                    <button type="button" class="tombol-tambah-jumlah w-10 py-2 text-lg font-bold hover:bg-green-600">+</button>
                            </div>`;
                        }
                        else if (element.classList.contains('kontrol-jumlah')){
                            const jumlahElement = element.querySelector('.jumlah-produk');
                            if (jumlahElement){
                                jumlahElement.value = jumlah;
                                jumlahElement.dataset.jumlahLama = jumlah;
                            }
                            element.dataset.jumlah = jumlah;
                        }
                    }else{
                        if (element.classList.contains('kontrol-jumlah')){
                            element.outerHTML = `
                                <button type="button"
                                    class="tombol-tambah block w-full bg-green-500 text-black py-2 rounded-lg text-sm font-semibold text-center hover:bg-gray-200 transition"
                                    data-produk-id="${produkId}">
                                    + Tambah
                                </button>`;
                        }
                    }
                });
            }
            function sinkronkanKeranjang(){
                const sudahLogin = document.body.dataset.sudahLogin === 'true';
                if (!sudahLogin){
                    return;
                }
                ambilKeranjang().then(data =>{
                    if (!data.success){
                        return;
                    }
                    const keranjang = data.keranjang || {};
                    sinkronkanHargaKeranjang(data);
                    const semuaProduk = new Set();
                    document.querySelectorAll('[data-produk-id]').forEach(function (element){
                        const produkId = element.dataset.produkId;
                        if (produkId){
                            semuaProduk.add(produkId);
                        }
                    });
                    semuaProduk.forEach(function (produkId){
                        const jumlah = keranjang[produkId] ? keranjang[produkId].jumlah : 0;
                        perbaruiSemuaTombolProduk(produkId, jumlah);
                    });
                })
                .catch(error =>{
                    console.error('Gagal mengambil data keranjang:',error);
                });
            }
            document.addEventListener('click', function (event){
                const tombolTambah = event.target.closest('.tombol-tambah');
                if (tombolTambah){
                    event.preventDefault();
                    event.stopPropagation();
                    const sudahLogin = document.body.dataset.sudahLogin === 'true';
                    if (!sudahLogin){
                        window.location.href = '{{ route('masuk') }}';
                        return;
                    }
                    const produkId = tombolTambah.dataset.produkId;
                    if (!produkId){
                        return;
                    }
                    kirimKeranjang('{{ route('keranjang.tambah') }}', produkId).then(data => {
                        if (!data.success){
                            alert(data.message);
                            return;
                        }
                        sinkronkanKeranjang();
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Terjadi kesalahan. Silakan coba lagi');
                    });
                    return;
                }
                const tombolTambahJumlah = event.target.closest('.tombol-tambah-jumlah');
                if (tombolTambahJumlah){
                    event.preventDefault();
                    event.stopPropagation();
                    const kontrol = tombolTambahJumlah.closest('.kontrol-jumlah');
                    if (!kontrol){
                        return;
                    }
                    const produkId = kontrol.dataset.produkId;
                    if (!produkId){
                        return;
                    }
                    kirimKeranjang('{{ route('keranjang.lebih') }}', produkId).then(data => {
                        if (!data.success){
                            alert(data.message);
                            return;
                        }
                        perbaruiSemuaTombolProduk(
                            produkId,
                            data.jumlah
                        );
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Terjadi kesalahan. Silakan coba lagi');
                    });
                    return;
                }
                const tombolKurang = event.target.closest('.tombol-kurang');
                if (tombolKurang){
                    event.preventDefault();
                    event.stopPropagation();
                    const kontrol = tombolKurang.closest('.kontrol-jumlah');
                    if (!kontrol){
                        return;
                    }
                    const produkId = kontrol.dataset.produkId;
                    if (!produkId){
                        return;
                    }
                    kirimKeranjang('{{ route('keranjang.kurang') }}', produkId).then(data => {
                        if (!data.success){
                            alert(data.message);
                            return;
                        }
                        sinkronkanKeranjang();
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Terjadi kesalahan. Silakan coba lagi');
                    });
                    return;
                }
            });

            document.addEventListener('input', function (event){
                const input = event.target.closest('.jumlah-produk');
                if (!input){
                    return;
                }
                input.value = input.value.replace(/[^0-9]/g, '');
            });
            document.addEventListener('focusin', function (event){
                const input = event.target.closest('.jumlah-produk');
                if (!input){
                    return;
                }
                input.select();
            });
            document.addEventListener('keydown', function (event){
                const input = event.target.closest('.jumlah-produk');
                if (!input){
                    return;
                }
                if (event.key === 'Enter'){
                    event.preventDefault();
                    input.blur();
                }
            });
            document.addEventListener('focusout', function (event){
                const input = event.target.closest('.jumlah-produk');
                if (!input){
                    return;
                }
                const kontrol = input.closest('.kontrol-jumlah');
                if (!kontrol){
                    return;
                }
                const produkId = kontrol.dataset.produkId;
                const jumlahLama = parseInt(input.dataset.jumlahLama) || 1;
                let jumlahBaru = parseInt(input.value);
                if (!jumlahBaru || jumlahBaru < 1){
                    input.value = jumlahLama;   
                    return;
                }
                if (jumlahBaru === jumlahLama){
                    input.value = jumlahLama;
                    return;
                }
                fetch('{{ route('keranjang.jumlah') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        produk_id: produkId,
                        jumlah: jumlahBaru
                    })
                })
                .then(response => response.json()).then(data => {
                    if (!data.success){
                        alert(data.message);
                        input.value = jumlahLama;
                        return;
                    }
                    perbaruiSemuaTombolProduk(
                        produkId,
                        data.jumlah
                    );
                })
                .catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan, Silakan coba lagi.');
                    input.value = jumlahLama;
                });
            });
            sinkronkanKeranjang();
        });
    </script>
</body>
</html>