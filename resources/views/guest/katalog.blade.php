@extends('layouts.guest')
@section('guest')
    <div class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 py-8">
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
                            placeholder="Tenda,Sleeping Bag..." />
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
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $data_barang->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
@endsection