<!-- resources/views/laporan/cetakDetailPdf.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Detail</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Detail</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->produk->id }}</td>
                    <td>{{ $detail->produk->nama }}</td>
                    <td>{{ 'Rp. ' . format_uang($detail->harga_jual) }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>{{ 'Rp. ' . format_uang($detail->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
