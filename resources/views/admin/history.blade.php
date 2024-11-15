@extends('layouts.admin')
@section('container')
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Nomor Resi</th>
                    <th>Jumlah Hari</th>
                    <th>Total</th>
                    <th>User</th>
                    <th>Barang</th>
                    <th>Qty</th>
                    <th>Denda</th>
                    <th>Tanggal Keluar</th>
                    <th>Tanggal Kembali</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $key => $transaction)
                    @php
                        $rowCount = $transaction->transaksi_details->count();
                        $isFirst = true;
                    @endphp
                    @foreach ($transaction->transaksi_details as $detail)
                        <tr>
                            @if ($isFirst)
                                <td class="align-top" rowspan="{{ $rowCount }}">{{ $transaction->no_resi }}</td>
                                <td class="align-top" rowspan="{{ $rowCount }}">
                                    @php
                                        $selisih = $transaction->tanggal_keluar->diffInDays(
                                            $transaction->tanggal_kembali,
                                        );
                                    @endphp
                                    @if ($selisih == 0)
                                        @php
                                            $selisih += 1;
                                        @endphp
                                    @endif
                                    {{ $selisih }} Hari
                                </td>
                                <td class="align-top" rowspan="{{ $rowCount }}">
                                    {{ number_format($transaction->total) }}</td>
                                <td class="align-top" rowspan="{{ $rowCount }}">{{ $transaction->user->username }}
                                </td>
                            @endif
                            <td>{{ $detail->barang->nama_barang }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>Rp.{{ number_format($detail->total_denda, 2, ',', '.') }}</td>
                            @if ($isFirst)
                                <td>{{ $transaction->tanggal_keluar->format('d-m-Y') }}</td>
                                <td>{{ $transaction->tanggal_kembali->format('d-m-Y') }}</td>
                            @endif
                        </tr>
                        @php
                            $isFirst = false;
                        @endphp
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
