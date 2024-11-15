@extends('layouts.admin')
@section('container')
    @if (session('loginSuccess'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil ',
                text: "{{ session('loginSuccess') }}"
            });
        </script>
    @endif
    @if (session('dendaSuccess'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil ',
                text: "{{ session('dendaSuccess') }}"
            });
        </script>
    @endif
    @if (session('selesaiSuccess'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil ',
                text: "{{ session('selesaiSuccess') }}"
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal ',
                text: "{{ session('error') }}"
            });
        </script>
    @endif
    <button data-bs-toggle="modal" data-bs-target="#tambahdenda" class="btn btn-outline-primary">Tambah Kategori
        Denda</button>
    <div class="container my-2">
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
                        <th>Tanggal Keluar</th>
                        <th>Tanggal Kembali</th>
                        <th>Action</th>
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
                                @if ($isFirst)
                                    <td>{{$transaction->tanggal_keluar->format('d-m-Y')}}</td>
                                    <td>{{$transaction->tanggal_kembali->format('d-m-Y')}}</td>
                                    <td class="align-top" rowspan="{{ $rowCount }}">
                                        <button data-bs-toggle="modal"
                                            data-bs-target="#selesaitransaksi{{ $transaction->id }}"
                                            class="btn btn-outline-info">Selesaikan</button>
                                    </td>
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
    </div>





    <div class="modal fade" id="tambahdenda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tambahdenda') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="namabarang">Jenis Denda:</label><br>
                        <input type="text" name="jenisdenda" id="jenisdenda"><br>
                        <label for="hargabarang">Jumlah Denda: (berapa x harga sewa)</label><br>
                        <input type="number" name="jumlahdenda" id="jumlahdenda"><br>

                </div>
                <div class="modal-footer">
                    <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    @foreach ($transaksi as $t)
        <div>
            <div class="modal fade" id="selesaitransaksi{{ $t->id }}" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Form Pengembalian Barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="transaksiselesai" method="POST">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nama Barang</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                                <th>Denda</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 0;
                                            @endphp
                                            @foreach ($t->transaksi_details as $details)
                                                @php
                                                    $total =
                                                        $details->qty *
                                                        str_replace('.', '', $details->barang->harga_barang);
                                                @endphp
                                                <tr>
                                                    <td>{{ $details->barang->nama_barang }}</td>
                                                    <td>{{ $details->qty }}</td>
                                                    <td>{{ $total }}</td>
                                                    <td>
                                                        <select class="form-select"
                                                            id="items[{{ $i }}][id_denda]"
                                                            name="items[{{ $i }}][id_denda]">
                                                            @foreach ($denda as $k)
                                                                <option value="{{ $k->id }}">
                                                                    {{ $k->tipe_denda }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                                <input type="hidden" name="items[{{ $i }}][id_barang]"
                                                    value="{{ $details->id_barang }}">
                                                <input type="hidden" name="items[{{ $i }}][qty]"
                                                    value="{{ $details->qty }}">
                                                <input type="hidden" name="items[{{ $i }}][id_detail]"
                                                    value="{{ $details->id }}">
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                            <input type="hidden" name="id_transaksi" value="{{ $t->id }}">
                                        </tbody>
                                    </table>
                                </div>
                                {{-- {{ $t->no_resi }} --}}
                                <div class="modal-footer">
                                    <button type="submit" value="Submit" class="btn btn-primary">Selesaikan</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endforeach
@endsection
