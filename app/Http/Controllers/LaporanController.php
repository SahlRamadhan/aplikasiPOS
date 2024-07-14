<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

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

        return view('laporan.index', compact('tanggalAwal', 'tanggalAkhir', 'filterType'));
    }

    public function getData($awal, $akhir)
    {
        $no = 1;
        $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');

            $pendapatan = $total_penjualan;
            $total_pendapatan += $pendapatan;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['penjualan'] = format_uang($total_penjualan);
            $row['pendapatan'] = format_uang($pendapatan);
            $row['aksi'] = '<button onclick="showDetail(`' . route('laporan.detail', $tanggal) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i> Show</button>';

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penjualan' => '',
            'pendapatan' => format_uang($total_pendapatan),
            'aksi' => '',
        ];

        return ['data' => $data, 'total_pendapatan' => $total_pendapatan];
    }

    public function data($awal, $akhir)
    {
        $dataInfo = $this->getData($awal, $akhir);
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
            $today = date('Y-m-d');
            $details = PenjualanDetail::with('produk')
            ->whereDate('created_at', $today)
                ->get();

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);

            $view = view('laporan.cetakPdfDetail', compact('details'))->render();
            $dompdf->loadHtml($view);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('Laporan-penjualan-detail-' . date('Y-m-d-His') . '.pdf');
        } else {
            $dataInfo = $this->getData($awal, $akhir);
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
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
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
