@extends('layouts.admin')
@section('container')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="container">
        <form action="" method="GET" class="mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="tglkeluar" class="form-label">Tanggal Keluar:</label>
                                <input type="text" name="tglkeluar" id="tglkeluar" class="form-control"
                                    placeholder="YYYY-MM-DD" value="{{ request('tglkeluar') }}">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="tglkembali" class="form-label">Tanggal Kembali:</label>
                                <input type="text" name="tglkembali" id="tglkembali" class="form-control"
                                    placeholder="YYYY-MM-DD" value="{{ request('tglkembali') }}">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search"></i> Cari
                            </button>
                            <a href="{{ route('admin.history') }}" class="btn btn-danger">
                                <i class="bi bi-x-circle"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
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
                                            <td class="align-middle" rowspan="{{ $rowCount }}">
                                                {{ $transaction->no_resi }}</td>
                                            <td class="align-middle" rowspan="{{ $rowCount }}">
                                                @php
                                                    $selisih = $transaction->tanggal_keluar->diffInDays(
                                                        $transaction->tanggal_kembali,
                                                    );
                                                    if ($selisih == 0) {
                                                        $selisih += 1;
                                                    }
                                                @endphp
                                                {{ $selisih }} Hari
                                            </td>
                                            <td class="align-middle" rowspan="{{ $rowCount }}">
                                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                            </td>
                                            <td class="align-middle" rowspan="{{ $rowCount }}">
                                                {{ $transaction->user->username }}
                                            </td>
                                        @endif
                                        <td>{{ $detail->barang->nama_barang }}</td>
                                        <td>{{ $detail->qty }}</td>
                                        <td>Rp {{ number_format($detail->total_denda, 0, ',', '.') }}</td>
                                        @if ($isFirst)
                                            <td>{{ $transaction->tanggal_keluar->format('d/m/Y') }}</td>
                                            <td>{{ $transaction->tanggal_kembali->format('d/m/Y') }}</td>
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
        </div>
    </div>
    <script>
        const config = {
            dateFormat: "Y-m-d",
            altFormat: "d/m/Y",
            altInput: true,
            locale: {
                firstDayOfWeek: 1
            }
        };

        const tglkeluarPicker = flatpickr("#tglkeluar", {
            ...config,
            onChange: function(selectedDates, dateStr) {
                if (selectedDates[0]) {
                    tglkembaliPicker.set('minDate', dateStr);

                    if (tglkembaliPicker.selectedDates[0] && tglkembaliPicker.selectedDates[0] < selectedDates[
                            0]) {
                        tglkembaliPicker.setDate(dateStr);
                    }
                }
            }
        });

        const tglkembaliPicker = flatpickr("#tglkembali", {
            ...config,
            onChange: function(selectedDates, dateStr) {
                if (selectedDates[0] && tglkeluarPicker.selectedDates[0]) {
                    if (selectedDates[0] < tglkeluarPicker.selectedDates[0]) {
                        alert('Tanggal kembali tidak boleh lebih kecil dari tanggal keluar');
                        this.setDate(tglkeluarPicker.selectedDates[0]);
                        return;
                    }
                }
            }
        });
    </script>
@endsection
