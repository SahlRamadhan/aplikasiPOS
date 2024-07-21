<!DOCTYPE html>
<html>

<head>
    <style>
        /* Gaya CSS untuk tampilan PDF */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .signature {
            margin-top: 50px;
            font-size: 14px;
            text-align: right;
        }

        .signature p {
            margin: 0;
        }

        .signature .name {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Laporan Penjualan Detail</h1>
    <p>Periode: {{ tanggal_indonesia($awal, false) }} - {{ tanggal_indonesia($akhir, false) }}</p>
    @if ($details->isEmpty())
        <p>Tidak ada data penjualan dalam rentang tanggal yang dipilih.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Total Penjualan</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $index => $transaksi)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ tanggal_indonesia($transaksi->created_at, false) }}</td>
                        <td>{{ format_uang($transaksi->total_item) }}</td>
                        <td>{{ format_uang($transaksi->total_harga) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="signature">
        <p>Pekalongan, {{ date('d F Y') }}</p>
        <br>
        <br>
        <br>
        <p class="name">{{ $user->name }}</p>
    </div>
</body>

</html>
