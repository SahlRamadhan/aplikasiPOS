<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiNativeHrdController extends Controller
{
    private $apiUrl = 'http://localhost/api-hrd/';

    public function index()
    {
        $response = Http::get($this->apiUrl);
        $data = $response->json();
        return view('karyawan.index', ['karyawan' => $data]);
    }

    public function store(Request $request)
    {
        $response = Http::asForm()->post($this->apiUrl, [
            'id' => $request->id,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'foto' => $request->foto
        ]);

        return redirect()->route('api_native_hrd.index')->with('status', $response->json());
    }

    public function update(Request $request, $id)
    {
        $response = Http::asForm()->put($this->apiUrl, [
            'id' => $id,
            'id_jabatan' => $request->id_jabatan,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'foto' => $request->foto
        ]);

        return redirect()->route('api_native_hrd.index')->with('status', $response->json());
    }

    public function destroy($id)
    {
        $response = Http::asForm()->delete($this->apiUrl, ['id' => $id]);

        return redirect()->route('api_native_hrd.index')->with('status', $response->json());
    }
}
