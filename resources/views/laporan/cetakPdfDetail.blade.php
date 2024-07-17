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
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Laporan Detail Penjualan</h1>
    <p>Periode: {{ tanggal_indonesia($awal, false) }} - {{ tanggal_indonesia($akhir, false) }}</p>
    @if($details->isEmpty())
        <p>Tidak ada data penjualan dalam rentang tanggal yang dipilih.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ tanggal_indonesia($detail->created_at->format('Y-m-d'), false) }}</td>
                        <td>{{ $detail->produk->nama }}</td>
                        <td>Rp. {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
