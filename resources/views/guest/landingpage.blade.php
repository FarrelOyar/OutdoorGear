@extends('layouts.guest')
@section('guest')
    
<div class="relative ">
    <div class="bg-gradient-to-r from-blue-600 to-blue-400 h-[60vh] flex items-center">
        <div class="max-w-6xl mx-auto px-4 text-center text-white">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Eksplorasi Alam dengan Gear Terbaik</h1>
            <p class="text-xl mb-8">Solusi penyewaan peralatan outdoor untuk petualangan Anda</p>
            <a href="/katalog"
            class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300">
            Lihat Katalog
        </a>
    </div>
</div>
</div>
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                <div class="text-blue-600 text-4xl mb-4">🏔️</div>
                <h3 class="text-xl font-semibold mb-2">Peralatan Premium</h3>
                <p class="text-gray-600">Koleksi lengkap peralatan outdoor berkualitas tinggi untuk berbagai kebutuhan.
                </p>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                <div class="text-blue-600 text-4xl mb-4">🛡️</div>
                <h3 class="text-xl font-semibold mb-2">Garansi Peralatan</h3>
                <p class="text-gray-600">Jaminan peralatan dalam kondisi prima dan terawat dengan baik.</p>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                <div class="text-blue-600 text-4xl mb-4">📱</div>
                <h3 class="text-xl font-semibold mb-2">Booking Online</h3>
                <p class="text-gray-600">Sistem pemesanan online yang mudah dan cepat.</p>
            </div>
        </div>
    </div>

    <div class="bg-blue-600 text-white py-16">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Siap Untuk Petualangan Berikutnya?</h2>
            <p class="mb-8">Dapatkan peralatan terbaik untuk kegiatan outdoor Anda</p>
            <a href="/login"
            class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300">
            Pesan Sekarang
        </a>
    </div>
</div>

@endsection