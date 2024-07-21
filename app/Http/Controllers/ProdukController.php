<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();
        return view('produk.index', compact('kategori'));
    }

    public function data()
    {
        $produk = Produk::with('kategori')->orderBy('id', 'asc')->get();

        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('id', function ($produk) {
                return '<span class="badge badge-success">' . $produk->id . '</span>';
            })
            ->addColumn('id_kategori', function ($produk) {
                return $produk->kategori->nama ?? '-';
            })
            ->addColumn('harga', function ($produk) {
                return format_uang($produk->harga);
            })
            ->addColumn('stok', function ($produk) {
                return format_uang($produk->stok);
            })
            ->addColumn('aksi', function ($produk) {
                return '
                    <button onclick="editForm(`' . route('produk.update', $produk->id) . '`)" class="btn btn-info"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`' . route('produk.destroy', $produk->id) . '`)" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                ';
            })
            ->rawColumns(['aksi', 'id'])
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
        // Ambil ID terakhir dari tabel produk_jadi
        $produk = Produk::latest()->first();
        $lastIdNumber = $produk ? (int) substr($produk->id, 3) : 0;

        // Tambahkan 1 pada ID terakhir untuk membuat ID baru
        $newIdNumber = $lastIdNumber + 1;
        $newId = 'PJ-' . str_pad($newIdNumber, 3, '0', STR_PAD_LEFT);

        // Cek apakah ID baru sudah ada di tabel
        while (Produk::find($newId)) {
            $newIdNumber++;
            $newId = 'PJ-' . str_pad($newIdNumber, 3, '0', STR_PAD_LEFT);
        }

        // Simpan produk baru dengan ID baru yang unik
        $produk = new Produk();
        $produk->id = $newId;
        $produk->nama = $request->nama;
        $produk->id_kategori = $request->id_kategori;
        $produk->harga = $request->harga;
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

        if (!$produk) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

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
