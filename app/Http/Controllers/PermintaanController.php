<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    public function index()
    {
        $client = new Client();
        $url = 'http://127.0.0.1:8000/api/permintaan';
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $data = json_decode($content, true)['data'];
        $produk = json_decode($content, true)['produk'];


        return view('permintaan.index', [
            'data' => $data,
            'produk' => $produk,

        ]);
    }

    public function data()
    {
        $client = new \GuzzleHttp\Client();
        $url = 'http://127.0.0.1:8000/api/permintaan';

        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $permintaanData = json_decode($content, true)['data'];

        

        $permintaan = collect($permintaanData);
        return datatables()
            ->of($permintaan)
            ->addIndexColumn()
            ->addColumn('id_permintaan', function ($permintaan) {
                return '<span class="badge badge-success">' . $permintaan['id'] . '<span>';
            })
            ->addColumn('id_produk_jadi', function ($permintaan) {
                return $permintaan['produkjadi']['nama'] ?? '';
            })
            ->addColumn('aksi', function ($permintaan) {
                return '
                <button type="button" onclick="deleteData(`' . route('permintaan.destroy', $permintaan['id']) . '`)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
            ';
            })
            ->rawColumns(['aksi', 'id_permintaan'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $client = new Client();
        $url = 'http://127.0.0.1:8000/api/permintaan';
        $parameter = [
            'nama' => $request->nama,
            'jumlah' => $request->jumlah,
            'status' => 'Pending',
            'id_produk_jadi' => $request->id_produk_jadi,
        ];

        $response = $client->request('POST', $url, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => $parameter
        ]);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        if ($statusCode == 201 && $contentArray['status']) {
            return redirect()->route('permintaan.index')->with('success', 'Permintaan berhasil dibuat');
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim permintaan ke API')->withInput();
        }
    }

    public function destroy(string $id)
    {
        $client = new Client();
        $url = 'http://127.0.0.1:8000/api/permintaan/' . $id;

        $response = $client->request('DELETE', $url);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        if ($statusCode == 200 && $contentArray['status']) {
            return redirect()->route('permintaan.index')->with('success', 'Permintaan berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus permintaan: ' . $contentArray['message']);
        }
    }
}