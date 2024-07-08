<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Nota Kecil</title>



<body onload="window.print()">
    {{-- <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button> --}}
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">MUZA MEBEL</h3>
        <p></p>
    </div>
    <br>
    <div>
        <p>Tanggal: {{ date('d-m-Y') }}</p>
        <p>Nama: {{ strtoupper(auth()->user()->name) }}</p>
    </div>
    <div class="clear-both" style="clear: both;"></div>
    <p>No Transaksi: {{ tambah_nol_didepan($penjualan->id_penjualan, 10) }}</p>
    <p class="text-center">===================================</p>
    <br>
    <table class="table table-bordered">
        <thead>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th class="text-end">Total</th>
        </thead>
        <tbody>
            @foreach ($detail as $item)
                <tr>
                    <td>{{ $item->produk->nama }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>Rp.{{ format_uang($item->harga_jual) }}</td>
                    <td align="right">Rp.{{ format_uang($item->jumlah * $item->harga_jual) }}</td>
                </tr>
            @endforeach
        </tbody>
        <table class="table table-borderless">
            <tfoot>
                <tr>
                    <th colspan="7">Total Harga</th>
                    <td align="right">Rp.{{ format_uang($penjualan->total_harga) }}</td>
                </tr>
                <tr>
                    <th colspan="7">Total Item</th>
                    <td align="right">{{ format_uang($penjualan->total_item) }}</td>
                </tr>
                <tr>
                    <th colspan="7">Diskon</th>
                    <td align="right">{{ format_uang($penjualan->diskon) }}%</td>
                </tr>
                <tr>
                    <th colspan="7">Total Bayar</th>
                    <td align="right">Rp.{{ format_uang($penjualan->bayar) }}</tdRp.>
                </tr>
                <tr>
                    <th colspan="7">Diterima</th>
                    <td align="right">Rp.{{ format_uang($penjualan->diterima) }}</td>
                </tr>
                <tr>
                    <th colspan="7" class="">Kembali</th>
                    <td align="right">Rp.{{ format_uang($penjualan->diterima - $penjualan->bayar) }}</td>
                </tr>
            </tfoot>
        </table>
    </table>

    <p class="text-center">===================================</p>
    <p class="text-center">-- TERIMA KASIH --</p>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
            body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight
        );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight=" + ((height + 50) * 0.264583);
    </script>
</body>

</html>
