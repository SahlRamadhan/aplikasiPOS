<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiDataPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penjualan = Penjualan::with(['pelanggan', 'user'])->get();
        $penjualandetail = PenjualanDetail::with('produk')->orderBy('id_penjualan', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Ditemukan',
            'penjualan' => $penjualan->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode_pelanggan' => $item->pelanggan->nama_pelanggan,
                    'id_user' => $item->user->name,
                    'total_item' => $item->total_item,
                    'total_harga' => $item->total_harga,
                    'diskon' => $item->diskon,
                    'bayar' => $item->bayar,
                    'tanggal' => Carbon::parse($item->created_at)->translatedFormat('j F Y'),
                ];
            }),
            'penjualandetail' => $penjualandetail,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penjualan = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();
        if ($penjualan->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Penjualan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail penjualan berhasil diambil',
            'data' => $penjualan,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
