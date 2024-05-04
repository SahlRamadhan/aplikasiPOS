<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pelanggan.index');
    }

    public function data()
    {
        $pelanggan = Pelanggan::orderBy('kode_pelanggan','desc')->get();

        return datatables()
            ->of($pelanggan)
            ->addIndexColumn()
            ->addColumn('kode_pelanggan', function ($pelanggan) {
                return '<span class="badge badge-success">' . $pelanggan->kode_pelanggan . '<span>';
            })
            ->addColumn('aksi', function ($pelanggan) {
                return '
                    <button type="button" onclick="editForm(`' . route('pelanggan.update', $pelanggan->id_pelanggan) . '`)" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></button>
                    <button type="button" onclick="deleteData(`' . route('pelanggan.destroy', $pelanggan->id_pelanggan) . '`)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_pelanggan'])
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
        $pelanggan = Pelanggan::latest()->first() ?? new Pelanggan();
        $kode_pelanggan =  $pelanggan ? (int) substr($pelanggan->kode_pelanggan, 5) + 1 : 1;

        $pelanggan = new Pelanggan();
        $pelanggan->kode_pelanggan = 'PLGN-' . tambah_nol_didepan( $kode_pelanggan, 6);
        $pelanggan->nama_pelanggan = $request->nama_pelanggan;
        $pelanggan->telepon = $request->telepon;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $member = Pelanggan::find($id);

        return response()->json($member);
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
        $produk = Pelanggan::find($id);
        $produk->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Pelanggan::find($id);
        $produk->delete();

        return response(null, 204);
    }
}
