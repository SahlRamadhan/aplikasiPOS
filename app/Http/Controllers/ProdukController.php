<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('produk.index');
    }

    public function data()
    {
        $produk = Produk::orderBy('id_produk', 'desc');

        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($produk) {
                return '<span class="badge badge-success">' . $produk->kode_produk . '</span>';
            })
            ->addColumn('harga_jual', function ($produk) {
                return format_uang($produk->harga_jual);
            })
            ->addColumn('stok', function ($produk) {
                return format_uang($produk->stok);
            })
            ->addColumn('aksi', function ($produk) {
                return '
                    <button onclick="editForm(`' . route('produk.update', $produk->id_produk) . '`)" class="btn btn-info"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`' . route('produk.destroy', $produk->id_produk) . '`)" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                ';
            })
            ->rawColumns(['aksi', 'kode_produk'])
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
        $produk = Produk::latest()->first() ?? new Produk();
        $kode_produk =  $produk ? (int) substr($produk->kode_produk, 5) + 1 : 1;

        $produk = new Produk();
        $produk->kode_produk = 'PRD-' . tambah_nol_didepan($kode_produk, 6);
        $produk->nama_produk = $request->nama_produk;
        $produk->harga_jual = $request->harga_jual;
        $produk->stok = $request->stok;
        $produk->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::find($id);

        return response()->json($produk);
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
        $produk = Produk::find($id);
        $produk->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return response(null, 204);
    }
}
