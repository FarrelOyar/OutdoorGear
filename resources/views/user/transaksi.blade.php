@extends('layouts.user')
@section('container')
    @php
        use App\Enums\TransactionStatus;
    @endphp
    <div class="overflow-x-auto shadow-md sm:rounded-lg mx-4 my-6">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th class="py-3 px-4 text-left">Nomor Resi</th>
                    <th class="py-3 px-4 text-left">Jumlah Hari</th>
                    <th class="py-3 px-4 text-left">Total</th>
                    <th class="py-3 px-4 text-left">Barang</th>
                    <th class="py-3 px-4 text-left">Qty</th>
                    <th class="py-3 px-4 text-left">Denda</th>
                    <th class="py-3 px-4 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($transaksi as $key => $transaction)
                    @php
                        $rowCount = $transaction->transaksi_details->count();
                        $isFirst = true;
                    @endphp

                    @foreach ($transaction->transaksi_details as $detail)
                        <tr class="border-b hover:bg-gray-50">
                            @if ($isFirst)
                                <td class="py-3 px-4 align-top" rowspan="{{ $rowCount }}">
                                    {{ $transaction->no_resi }}
                                </td>
                            @endif


                            @if ($isFirst)
                                @php
                                    $selisihhari = $transaction->tanggal_keluar->diffInDays(
                                        $transaction->tanggal_kembali,
                                    );
                                @endphp
                                @if ($selisihhari == 0)
                                    @php
                                        $selisihhari += 1;
                                    @endphp
                                @endif
                                <td class="py-3 px-4 align-top" rowspan="{{ $rowCount }}">
                                    {{ $selisihhari }} Hari
                                </td>
                                <td class="py-3 px-4 align-top" rowspan="{{ $rowCount }}">
                                    {{ number_format($transaction->total) }}
                                </td>
                            @endif
                            <td class="py-3 px-4">{{ $detail->barang->nama_barang }}</td>
                            <td class="py-3 px-4">{{ $detail->qty }}</td>
                            @if ($detail->total_denda  )
                            <td class="py-3 px-4">{{ $detail->total_denda }}</td>        
                            @else
                            <td class="py-3 px-4">-</td>        
                            @endif
                            @if ($isFirst)
                                <td class="py-3 px-4 align-top" rowspan="{{ $rowCount }}">
                                    @if ($transaction->status === TransactionStatus::SELESAI)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                    @else
                                        <a href="/resi/{{ $transaction->id }}"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Resi
                                        </a>
                                    @endif
                                </td>
                            @endif

                            @php
                                $isFirst = false;
                            @endphp
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-8 mx-4 my-6 flex justify-start space-x-4">
        <a href="/" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
            Kembali
        </a>
    </div>
@endsection
