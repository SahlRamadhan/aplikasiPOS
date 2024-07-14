<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('kategori.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function data()
    {
        $kategori = Kategori::orderBy('id', 'asc')->get();

        return datatables()
            ->of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                return '
                    <button type="button" onclick="editForm(`' . route('kategori.update', $kategori->id) . '`)" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></button>
                    <button type="button" onclick="deleteData(`' . route('kategori.destroy', $kategori->id) . '`)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_kategori'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $kategori = Kategori::latest()->first() ?? new Kategori();

        $kategori = new Kategori();
        $kategori->id = $request->id;
        $kategori->nama = $request->nama;
        $kategori->keterangan = $request->keterangan;
        $kategori->save();

        return response()->json('Data berhasil disimpan', 200);
    }
    public function show(string $id)
    {
        $kategori = Kategori::find($id);

        return response()->json($kategori);
    }
    public function update(Request $request, string $id)
    {
        $kategori = Kategori::find($id);
        $kategori->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }


    public function destroy(string $id)
    {
        $kategori = Kategori::find($id);
        $kategori->delete();

        return response(null, 204);
    }
}
