<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi Outdoorgear Rental</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: F4;
            /* 210mm x 330mm */
            margin: 1.5cm;
        }

        @media print {
            body {
                width: 210mm;
                height: 330mm;
            }
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            font-size: 12pt;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            /* F4 width */
            margin: 0 auto;
            background: white;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .info-section {
            margin-bottom: 15px;
        }

        .info-container {
            width: 100%;
            margin-bottom: 15px;
            display: table;
        }

        .info-block {
            width: 49%;
            display: inline-block;
            vertical-align: top;
        }

        .info-grid {
            margin-bottom: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11pt;
        }

        .table th,
        .table td {
            padding: 6px;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f3f4f6;
        }

        .warning-box {
            background-color: #fefce8;
            padding: 10px;
            border: 1px solid #fef08a;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 11pt;
        }

        .signature-section {
            margin-top: 20px;
            text-align: center;
            page-break-inside: avoid;
        }

        .signature-column {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }

        .signature-box {
            border-bottom: 1px solid #000;
            height: 60px;
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 10pt;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 14pt;
            margin-bottom: 8px;
            font-weight: bold;
        }

        /* Specific adjustments for total info */
        .total-info {
            width: 100%;
            margin: 15px 0;
            border-top: 2px solid #000;
            padding-top: 10px;
        }

        .total-info p {
            margin: 5px 0;
        }

        @media print {
            .page-break {
                page-break-before: always;
            }

            .no-break {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>OUTDOORGEAR RENTAL</h1>
            <p>Jl. Gunung Kembar No. 99, Malang</p>
        </div>

        <div class="info-section">
            <p><strong>No. Resi:</strong> {{ $transaksi->no_resi }}</p>
            <p><strong>Tanggal Order:</strong> {{ $transaksi->created_at->format('d-m-Y') }}</p>
        </div>

        <div class="info-container">
            <div class="info-block">
                <h2 class="section-title">Informasi Penyewa</h2>
                <div class="info-grid">
                    <div>
                        <p><strong>Nama:</strong> {{ $transaksi->user->username }}</p>
                        <p><strong>NIK:</strong> {{ $transaksi->user->nik }}</p>
                        <p><strong>Email:</strong> {{ $transaksi->user->email }}</p>
                    </div>
                </div>
            </div>

            <div class="info-block">
                <h2 class="section-title">Periode Sewa</h2>
                <div class="info-grid">
                    <div>
                        <p><strong>Tanggal Keluar:</strong> {{ $transaksi->tanggal_keluar->format('d-m-y') }}</p>
                        <p><strong>Tanggal Kembali:</strong> {{ $transaksi->tanggal_kembali->format('d-m-y') }}</p>
                        <p><strong>Durasi Sewa:</strong>
                            {{ $transaksi->tanggal_keluar->diffInDays($transaksi->tanggal_kembali) }} Hari</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h2 class="section-title">Detail Barang</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th class="align-right">Harga/Hari</th>
                        <th class="align-center">Qty</th>
                        <th class="align-right">Subtotal</th>
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
                        <tr>
                            <td>{{ $t->barang->nama_barang }}</td>
                            <td class="align-right">Rp.{{ $t->barang->harga_barang }}</td>
                            <td class="align-center">{{ $t->qty }}</td>
                            <td class="align-right">Rp.{{ $p }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="info-block">
            <div class="info-grid">
                <div>
                    <p><strong>Total Harga per Hari:</strong> Rp.{{ number_format($total, 2, ',', '.') }}</p>
                    <p><strong>Jumlah Hari:</strong>
                        {{ $transaksi->tanggal_keluar->diffInDays($transaksi->tanggal_kembali) }} hari</p>
                    <p><strong>Total Pembayaran:</strong> Rp.{{ number_format($transaksi->total, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>


        <div class="warning-box">
            <h3 style="font-weight: bold; margin-bottom: 8px;">Catatan Penting:</h3>
            <ul style="list-style-position: inside;">
                <li>Harap menjaga kebersihan dan keutuhan peralatan</li>
                <li>Pengembalian maksimal pukul 17:00 WIB</li>
                <li>Kerusakan atau kehilangan barang menjadi tanggung jawab penyewa</li>
            </ul>
        </div>

        <div class="signature-section">
            <div class="signature-column">
                <p style="font-weight: bold;">Petugas</p>
                <div class="signature-box"></div>
                <p>( Admin )</p>
            </div>
            <div class="signature-column">
                <p style="font-weight: bold;">Penyewa</p>
                <div class="signature-box"></div>
                <p>( {{ $transaksi->user->username }} )</p>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih telah menyewa di OutdoorGear Rental</p>
            <p>Selamat berpetualang!</p>
        </div>
    </div>
</body>

</html>
