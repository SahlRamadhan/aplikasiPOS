<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .signature {
            margin-top: 40px;
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
    <h2>Laporan Pendapatan</h2>
    <p>Periode: {{ tanggal_indonesia($awal, false) }} - {{ tanggal_indonesia($akhir, false) }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Penjualan</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row['DT_RowIndex'] }}</td>
                    <td>{{ $row['tanggal'] }}</td>
                    <td>{{ $row['penjualan'] }}</td>
                    <td>{{ $row['pendapatan'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td><strong>Total Pendapatan</strong></td>
                <td></td>
                <td>{{ format_uang($total_pendapatan) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <p>Pekalongan, {{ date('d F Y') }}</p>
        <br><br><br>
        <p class="name">{{ $user->name }}</p>
    </div>
</body>

</html>
