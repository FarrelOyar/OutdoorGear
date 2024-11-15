@extends('layouts.usernobtn')
@section('container')
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-lg my-8">
        <!-- Header Resi -->
        <div class="text-center border-b-2 pb-4 mb-4">
            <h1 class="text-2xl font-bold">OUTDOORGEAR RENTAL</h1>
            <p class="text-gray-600">Jl. Gunung Kembar No. 99, Malang</p>
        </div>

        <!-- Nomor Resi dan Tanggal -->
        <div class="flex justify-between mb-6">
            <div>
                <p class="font-semibold">No. Resi: {{ $transaksi->no_resi }}</p>
                <p>Tanggal Order: {{ $transaksi->created_at->format('d-m-Y') }}</p>
            </div>

        </div>

        <!-- Informasi Penyewa -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Informasi Penyewa</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p><span class="font-medium">Nama:</span>{{ $transaksi->user->username }}</p>
                    <p><span class="font-medium">NIK:</span> {{ $transaksi->user->nik }}</p>
                </div>
                <div>
                    <p><span class="font-medium">Email:</span> {{ $transaksi->user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Periode Sewa -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Periode Sewa</h2>
            <div class="grid grid-cols-2 gap-4">
                <p><span class="font-medium">Tanggal Keluar:</span> {{ $transaksi->tanggal_keluar->format('d-m-y') }}</p>
                <p><span class="font-medium">Tanggal Kembali:</span> {{ $transaksi->tanggal_kembali->format('d-m-y') }}</p>
            </div>
            <p class="mt-2"><span class="font-medium">Durasi
                    Sewa:</span>{{ $transaksi->tanggal_keluar->diffInDays($transaksi->tanggal_kembali) }} Hari</p>
        </div>

        <!-- Detail Barang -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Detail Barang</h2>
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Nama Barang</th>
                        <th class="px-4 py-2 text-right">Harga/Hari</th>
                        <th class="px-4 py-2 text-center">Qty</th>
                        <th class="px-4 py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi->transaksi_details as $t)
                        @php
                            $p = number_format(
                                (int) preg_replace('/\./', '', $t->barang->harga_barang) * $t->qty,
                                2,
                                ',',
                                '.',
                            );
                        @endphp
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $t->barang->nama_barang }}</td>
                            <td class="px-4 py-2 text-right">Rp.{{ $t->barang->harga_barang }}</td>
                            <td class="px-4 py-2 text-center">{{ $t->qty }}</td>
                            <td class="px-4 py-2 text-right">Rp.{{ $p }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total dan Pembayaran -->
        <div class="border-t-2 pt-4">
            <div class="flex justify-between mb-2">
                <span class="font-medium">Total Harga per Hari:</span>
                <span>Rp.{{ $totalFormatted = number_format($total, 2, ',', '.') }}</span>
            </div>
            <div class="flex justify-between mb-2">
                @php
                    $selisihhari = $transaksi->tanggal_keluar->diffInDays($transaksi->tanggal_kembali);
                @endphp
                @if ($selisihhari == 0)
                    @php
                        $selisihhari += 1;
                    @endphp
                @endif
                <span class="font-medium">Jumlah Hari:</span>
                <span>{{ $selisihhari }} hari</span>
            </div>

            <div class="flex justify-between text-lg font-bold">
                <span>Total Pembayaran:</span>
                <span>Rp.{{ number_format($transaksi->total, 2, ',', '.') }}</span>
            </div>
        </div>

        <!-- Catatan Penting -->
        <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
            <h3 class="font-semibold text-yellow-800 mb-2">Catatan Penting:</h3>
            <ul class="list-disc list-inside text-sm text-yellow-800 space-y-1">
                <li>Harap menjaga kebersihan dan keutuhan peralatan</li>
                <li>Pengembalian maksimal pukul 17:00 WIB</li>
                <li>Kerusakan atau kehilangan barang menjadi tanggung jawab penyewa</li>
            </ul>
        </div>

        <!-- Syarat dan Ketentuan -->


        <!-- Tanda Tangan -->
        <div class="mt-8 grid grid-cols-2 gap-4 text-center">
            <div>
                <p class="font-medium">Petugas</p>
                <div class="h-20 border-b mt-12"></div>
                <p>( Admin )</p>
            </div>
            <div>
                <p class="font-medium">Penyewa</p>
                <div class="h-20 border-b mt-12"></div>
                <p>( {{ $transaksi->user->username }} )</p>
            </div>
        </div>

        <!-- Tombol Cetak -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="/print/{{ $transaksi->id }}"
                class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition duration-200">Cetak Resi</a>
            {{-- <form action="{{ route('cetakResi', ['idtransaksi'=>$transaksi->id]) }}" method="get"> --}}
            {{-- <button class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition duration-200">
                    Cetak Resi
                </button> --}}
            {{-- </form> --}}
            <a href="/transaksi"
                class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                Riwayat Transaksi
            </a>
        </div>

        <!-- Footer Resi -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Terima kasih telah menyewa di OutdoorGear Rental</p>
            <p>Selamat berpetualang!</p>
        </div>
    </div>

    <style>
        @media print {
            .max-w-3xl {
                max-width: none;
                margin: 0;
                padding: 20px;
            }

            button,
            a {
                display: none;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .shadow-lg {
                box-shadow: none;
            }

            .bg-yellow-50 {
                background-color: #fefce8 !important;
            }
        }
    </style>
@endsection
