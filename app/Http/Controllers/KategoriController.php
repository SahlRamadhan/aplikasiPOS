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
            ->addColumn('kode_kategori', function ($kategori) {
                return '<span class="badge badge-success">' . $kategori->kode_kategori . '<span>';
            })
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
        $kode_kategori =  $kategori ? (int) substr($kategori->kode_kategori, 5) + 1 : 1;

        $kategori = new Kategori();
        $kategori->kode_kategori = 'KTG-' . tambah_nol_didepan($kode_kategori, 3);
        $kategori->nama = $request->nama;
        $kategori->keterangan = $request->keterangan;
        $kategori->save();

        return response()->json('Data berhasil disimpan', 200);
    }
}
