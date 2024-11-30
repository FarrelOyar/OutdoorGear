@extends('layouts.usernobtn')
@section('container')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    @if (session('checkoutFail'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Gagal! ',
                text: "{{ session('checkoutFail') }}"
            });
        </script>
    @endif
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="py-3 px-4 text-left"></th>
                <th class="py-3 px-4 text-left">Produk</th>
                <th class="py-3 px-4 text-left"></th>
                <th class="py-3 px-4 text-left">Price</th>
                <th class="py-3 px-4 text-left">Quantity</th>
                <th class="py-3 px-4 text-left">Total Price</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($cart as $c)
                <tr class="border-b">
                    <td class="py-3 px-4">{{ $i }}</td>
                    <td class="py-3 px-4">
                        <div class="flex justify-center">
                            <img src="storage/foto_barang/{{ $c->barang->foto_barang }}" alt="{{ $c->barang->nama_barang }}"
                                class="w-20 h-20 object-cover">
                        </div>
                    </td>
                    <td class="py-3 px-4">{{ $c->barang->nama_barang }}</td>
                    <td class="py-3 px-4">Rp.{{ $c->barang->harga_barang }}</td>
                    <td class="py-3 px-4 flex items-center">
                        <label class="px-4 py-2 bg-gray-200 w-16 text-center qty-input">{{ $c->qty }}</label>
                    </td>
                    @php
                        $t = number_format(
                            (int) preg_replace('/\./', '', $c->barang->harga_barang) * $c->qty,
                            2,
                            ',',
                            '.',
                        );
                    @endphp
                    <td class="py-3 px-4">Rp.{{ $t }}</td>
                    </td>

                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
        </tbody>
    </table><br>

    <div class="flex justify-end space-x-4 py-4 px-5">
        <span class="font-bold">Total Sewa Per Hari: Rp.{{ $totalFormatted = number_format($total, 2, ',', '.') }}</span>
    </div>

    <div class="px-5">
        <form action="checkout" method="post" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="tglkeluar" class="block font-medium">Tanggal Keluar:</label>
                    <input type="text" name="tglkeluar" id="tglkeluar" class="w-full p-2 border rounded-lg"
                        placeholder="Y-m-d" required>
                </div>
                <div class="space-y-2">
                    <label for="tglkembali" class="block font-medium">Tanggal Kembali:</label>
                    <input type="text" name="tglkembali" id="tglkembali" class="w-full p-2 border rounded-lg"
                        placeholder="Y-m-d" required>
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="font-medium">Jumlah Hari Sewa:</span>
                    <span id="jumlahHari" class="font-bold">0 hari</span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-medium">Total Pembayaran:</span>
                    <span id="totalPembayaran" class="font-bold text-lg text-orange-600">Rp. 0</span>
                </div>
            </div>
            @php
                $i = 0;
            @endphp
            @foreach ($cart as $c)
                <input type="hidden" name="total_hargacart" value="{{ $total }}" id="hargaPerHari">
                <input type="hidden" name="items[{{ $i }}][id_product]" value="{{ $c->id_barang }}">
                <input type="hidden" name="items[{{ $i }}][qty]" value="{{ $c->qty }}">
                @php
                    $i++;
                @endphp
            @endforeach

            <div class="flex space-x-4 mt-6">
                <a href="cart" class="bg-blue-500 hover:bg-blue-700 px-6 py-3 rounded-lg text-white">
                    Back
                </a>
                <button type="submit" class="bg-orange-500 hover:bg-orange-700 px-6 py-3 rounded-lg text-white">
                    Checkout
                </button>
            </div>
        </form>
    </div>
    <script>
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(angka);
        }

        function hitungSelisihHari(tglKeluar, tglKembali) {
            const oneDay = 24 * 60 * 60 * 1000;
            const firstDate = new Date(tglKeluar);
            const secondDate = new Date(tglKembali);
            firstDate.setHours(0, 0, 0, 0);
            secondDate.setHours(0, 0, 0, 0);

            const diffDays = Math.round((secondDate - firstDate) / oneDay);
            if (diffDays == 0) {
                return diffDays + 1;
            }
            return diffDays;
        }

        function updateTotal(startDate, endDate) {
            const hargaPerHari = parseFloat(document.getElementById('hargaPerHari').value);

            if (startDate && endDate) {
                const jumlahHari = hitungSelisihHari(startDate, endDate);
                const totalPembayaran = hargaPerHari * jumlahHari;

                document.getElementById('jumlahHari').textContent = `${jumlahHari} hari`;
                document.getElementById('totalPembayaran').textContent = formatRupiah(totalPembayaran);
            }
        }
        const tglkeluarPicker = flatpickr("#tglkeluar", {
            dateFormat: "Y-m-d",
            minDate: "today",
            onChange: function(selectedDates, dateStr) {
                tglkembaliPicker.set('minDate', dateStr);
                if (tglkembaliPicker.selectedDates[0] && tglkembaliPicker.selectedDates[0] < selectedDates[0]) {
                    tglkembaliPicker.setDate(dateStr);
                }

                if (tglkembaliPicker.selectedDates[0]) {
                    updateTotal(dateStr, tglkembaliPicker.selectedDates[0]);
                }
            }
        });
        const tglkembaliPicker = flatpickr("#tglkembali", {
            dateFormat: "Y-m-d",
            minDate: "today",
            onChange: function(selectedDates, dateStr) {
                if (!tglkeluarPicker.selectedDates[0]) {
                    alert('Silahkan pilih tanggal keluar terlebih dahulu');
                    this.clear();
                    return;
                }
                if (selectedDates[0] < tglkeluarPicker.selectedDates[0]) {
                    alert('Tanggal kembali tidak boleh lebih kecil dari tanggal keluar');
                    this.setDate(tglkeluarPicker.selectedDates[0]);
                    return;
                }

                updateTotal(tglkeluarPicker.selectedDates[0], dateStr);
            }
        });
    </script>
@endsection
