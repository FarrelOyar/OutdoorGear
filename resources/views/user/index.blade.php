@extends('layouts.user')
@section('container')
    @if (session('loginSuccess'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Welcome!',
                text: "{{ session('loginSuccess') }}",
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif
    @if (session('cartSuccess'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Added to Cart!',
                text: "{{ session('cartSuccess') }}",
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif
    <div class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-12 text-center">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Selamat Datang di OutdoorGear Rent!</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Temukan berbagai barang berkualitas tinggi kami yang tersedia
                    untuk disewa dengan harga bersaing!</p>
            </div>
            <div class="my-4">
                <form class="max-w-md mx-auto" action="" method="GET">
                    <label for="default-search"
                    class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" name="keyword" id="keyword"
                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Tenda,Sleeping Bag..."  />
                    <button type="submit"
                    class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                </div>
            </form>
        </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($data_barang as $b)
                    <div
                        class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl flex flex-col h-full">
                        <div class="relative">
                            <img src="{{ asset('storage/foto_barang/' . $b->foto_barang) }}" alt="{{ $b->nama_barang }}"
                                class="w-full h-56 object-cover">
                            @if ($b->stock - $b->barang_keluar <= 0)
                                <div
                                    class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 m-2 rounded-full text-sm">
                                    Out of Stock
                                </div>
                            @endif
                        </div>
                        <div class="p-5 flex-grow flex flex-col">
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $b->nama_barang }}</h2>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-lg font-bold text-blue-600">Rp.
                                    {{ $b->harga_barang }}/day</span>
                                <span class="text-sm text-gray-500">Stock: {{ $b->stock - $b->barang_keluar }}</span>
                            </div>

                            <p class="text-gray-600 text-sm mb-4 flex-grow" id="description{{ $b->id }}">
                                {{ Str::limit($b->deskripsi, 100) }}
                            </p>
                            <div class="mt-auto">
                                <form action="add_cart" method="post">
                                    @csrf
                                    <input type="hidden" name="id_user" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="id_barang" value="{{ $b->id }}">
                                    @if ($b->stock - $b->barang_keluar > 0)
                                        <button type="submit"
                                            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Tambahkan ke Keranjang
                                        </button>
                                    @else
                                        <button disabled
                                            class="w-full bg-gray-300 text-gray-500 py-2 rounded-lg cursor-not-allowed">
                                            Stock Habis
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $data_barang->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
    {{-- 
    <style>
        /* Pagination Container */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 2rem 0;
            gap: 4px;
        }
    
        /* Individual Page Items */
        .pagination > * {
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #1a56db;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s;
        }
    
        /* Active State */
        .pagination .active {
            background: #1a56db;
            color: white;
            border-color: #1a56db;
        }
    
        /* Hover State */
        .pagination a:hover:not(.active) {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }
    
        /* Disabled State */
        .pagination span[aria-disabled="true"] {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f8fafc;
        }
    
        /* Previous/Next Text */
        .pagination span {
            white-space: nowrap;
        }
    
        /* Results Text */
        .pagination-results {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-top: 1rem;
        }
    </style>
     --}}
@endsection
