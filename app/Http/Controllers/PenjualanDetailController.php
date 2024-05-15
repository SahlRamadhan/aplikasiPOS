<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use Illuminate\Http\Request;

class PenjualanDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        // Cek apakah ada transaksi yang sedang berjalan
        if ($id_penjualan = session('id_penjualan')) {
            // Jika ada, ambil detail penjualan dan data pelanggan terkait
            $penjualan = Penjualan::find($id_penjualan);
            $pelangganSelected = $penjualan->pelanggan ?? new Pelanggan();
            $produk = Produk::orderBy('nama_produk')->get();
            $pelanggan = Pelanggan::orderBy('nama_pelanggan')->get();

            // Tampilkan view dengan data yang diperlukan
            return view('penjualan_detail.index', compact('produk', 'pelanggan','id_penjualan', 'penjualan', 'pelangganSelected'));
        } else {
            // Jika tidak ada transaksi berjalan, arahkan pengguna ke halaman baru sesuai peran mereka
            if (auth()->user()->level == 1) {
                return redirect()->route('transaksi.baru');
            } else {
                return redirect()->route('dashboard');
            }
        }
    }

    public function data($id)
    {
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="badge badge-success">' . $item->produk['kode_produk'] . '</span';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_jual']  = 'Rp. ' . format_uang($item->harga_jual);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="' . $item->id_penjualan_detail . '" value="' . $item->jumlah . '">';
            $row['subtotal']    = 'Rp. ' . format_uang($item->subtotal);
            $row['aksi']        = '<button onclick="deleteData(`' . route('transaksi.destroy', $item->id_penjualan_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>';
            $data[] = $row;

            $total += $item->harga_jual * $item->jumlah - (($item->diskon * $item->jumlah) / 100 * $item->harga_jual);
            $total_item += $item->jumlah;
        }
        // Berlaku diskon jika total transaksi lebih dari 1000000
        $diskon = $total > 1000000 ? 10 : 0;
        $data[] = [
            'kode_produk' => '
                <div class="total hide">' . $total . '</div>
                <div class="total_item hide">' . $total_item . '</div>',
            'nama_produk' => '',
            'harga_jual'  => '',
            'jumlah'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Mengambil produk berdasarkan ID yang diberikan dalam permintaan
        $produk = Produk::where('id_produk', $request->id_produk)->first();

        if (!$produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        // Hitung total belanja dari semua item dalam transaksi
        $totalBelanja = PenjualanDetail::where('id_penjualan', $request->id_penjualan)
        ->sum('subtotal');

        // Berlaku diskon jika total belanja lebih dari 1000000
        $diskon = $totalBelanja > 1000000 ? 10 : 0;

        $detail = new PenjualanDetail();
        $detail->id_penjualan = $request->id_penjualan;
        $detail->id_produk = $produk->id_produk;
        $detail->harga_jual = $produk->harga_jual;
        $detail->jumlah = 1;
       

        $detail->subtotal = $produk->harga_jual - ($diskon / 100 * $produk->harga_jual);
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Mengambil detail penjualan berdasarkan ID yang diberikan dalam permintaan
        $detail = PenjualanDetail::find($id);

        // Memperbarui jumlah dan subtotal dari detail penjualan
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_jual * $request->jumlah - (($detail->diskon * $request->jumlah) / 100 * $detail->harga_jual);
        $detail->update();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
         // Hitung total pembayaran setelah diskon
        $bayar   = $total - ($diskon / 100 * $total);
        // Hitung kembalian jika uang diterima tidak nol
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        // Format data untuk ditampilkan
        $data    = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar) . ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali) . ' Rupiah'),
        ];

        return response()->json($data);
    }
}
