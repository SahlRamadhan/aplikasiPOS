<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');
        $filterType = $request->query('filter_type', 'tanggal');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }
        $dataInfo = $this->getData($tanggalAwal, $tanggalAkhir, $filterType);
        $total_pendapatan = $dataInfo['total_pendapatan'];

        return view('laporan.index', compact('tanggalAwal', 'tanggalAkhir', 'filterType', 'total_pendapatan'));
    }

    public function getData($awal, $akhir, $filterType)
    {
        $no = 1;
        $data = [];
        $total_pendapatan = 0;

        if ($filterType == 'tanggal') {
            // Logika untuk filter berdasarkan tanggal
            while (strtotime($awal) <= strtotime($akhir)) {
                $tanggal = $awal;
                $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

                $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');

                $pendapatan = $total_penjualan;
                $total_pendapatan += $pendapatan;

                $row = [
                    'DT_RowIndex' => $no++,
                    'tanggal' => tanggal_indonesia($tanggal, false),
                    'penjualan' => format_uang($total_penjualan),
                    'pendapatan' => format_uang($pendapatan),
                    'aksi' => '<button onclick="showDetail(' . route('laporan.detail', $tanggal) . ')" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i> Show</button>',
                ];

                $data[] = $row;
            }

            // Jika tidak ada transaksi pada rentang tanggal, tambahkan satu baris kosong
            if (count($data) == 0) {
                $data[] = [
                    'DT_RowIndex' => '',
                    'tanggal' => '',
                    'penjualan' => '',
                    'pendapatan' => '',
                    'aksi' => '',
                ];
            }
        } elseif ($filterType == 'penjualan') {
            // Logika untuk filter berdasarkan penjualan detail
            $transaksis = Penjualan::whereBetween('created_at', [$awal . ' 00:00:00', $akhir . ' 23:59:59'])
            ->orderBy('created_at', 'asc')
            ->get();

            foreach ($transaksis as $transaksi) {
                $tanggal = $transaksi->created_at->format('Y-m-d');

                // Hitung total penjualan pada tanggal ini
                $total_penjualan = $transaksi->bayar;

                $row = [
                    'DT_RowIndex' => $no++,
                    'tanggal' => tanggal_indonesia($tanggal, false),
                    'penjualan' => format_uang($total_penjualan),
                    'pendapatan' => format_uang($total_penjualan), // Misalkan pendapatan sama dengan total penjualan
                    'aksi' => '<button onclick="showDetail(\'' . route('laporan.detail', ['tanggal' => $tanggal]) . '\')" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i> Show</button>',
                ];

                $data[] = $row;
                $total_pendapatan += $total_penjualan;
            }

            // Jika tidak ada transaksi pada rentang tanggal, tambahkan satu baris kosong
            if (count($transaksis) == 0) {
                $data[] = [
                    'DT_RowIndex' => '',
                    'tanggal' => '',
                    'penjualan' => '',
                    'pendapatan' => '',
                    'aksi' => '',
                ];
            }
        }

        return ['data' => $data, 'total_pendapatan' => $total_pendapatan];
    }

    public function data($awal, $akhir, $filterType)
    {
        $dataInfo = $this->getData($awal, $akhir, $filterType);
        $data = $dataInfo['data'];

        return datatables()
            ->of($data)
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function exportPDF($awal, $akhir, Request $request)
    {
        $filterType = $request->query('filter_type');

        if ($filterType == 'penjualan') {
            $details = PenjualanDetail::with('produk')
            ->whereBetween('created_at', [$awal . ' 00:00:00', $akhir . ' 23:59:59'])
            ->get();

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);

            $view = view('laporan.cetakPdfDetail', compact('details', 'awal', 'akhir'))->render();

            $dompdf->loadHtml($view);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('Laporan-penjualan-detail-' . date('Y-m-d-His') . '.pdf');
        } else {
            $dataInfo = $this->getData($awal, $akhir, $filterType);
            $data = $dataInfo['data'];
            $total_pendapatan = $dataInfo['total_pendapatan'];

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);

            $view = view('laporan.cetakPdf', compact('awal', 'akhir', 'data', 'total_pendapatan'))->render();
            $dompdf->loadHtml($view);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('Laporan-pendapatan-' . date('Y-m-d-His') . '.pdf');
        }
    }

    public function detail($tanggal)
    {
        $detail = PenjualanDetail::with('produk')
            ->whereDate('created_at', $tanggal)
            ->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('id', function ($detail) {
                return $detail->produk->id;
            })
            ->addColumn('nama', function ($detail) {
                return $detail->produk->nama;
            })
            ->addColumn('harga', function ($detail) {
                return 'Rp. ' . format_uang($detail->harga_jual);
            })
            ->addColumn('jumlah', function ($detail) {
                return $detail->jumlah;
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. ' . format_uang($detail->subtotal);
            })
            ->make(true);
    }

    public function filterPenjualan(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // Ambil data penjualan detail berdasarkan tanggal
        $penjualanDetails = PenjualanDetail::with('produk')
            ->whereBetween('created_at',[$tanggalAwal . ' 00:00:00', $tanggalAkhir . ' 23:59:59'])
            ->get();

        // Format data untuk DataTables
        $data = [];
        foreach ($penjualanDetails as $index => $detail) {
            $row = [
                'DT_RowIndex' => $index + 1,
                'id' => $detail->produk->id,
                'nama' => $detail->produk->nama,
                'harga' => 'Rp. ' . format_uang($detail->harga_jual),
                'jumlah' => $detail->jumlah,
                'subtotal' => 'Rp. ' . format_uang($detail->subtotal),
            ];
            $data[] = $row;
        }

        return response()->json(['data' => $data]);
    }

}
