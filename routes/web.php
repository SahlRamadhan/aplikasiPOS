<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualanDetailController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::group(['middleware' => 'auth'], function () {
    //route untuk menuju menu produk
    Route::get('produk/data', [ProdukController::class, 'data'])->name('produk.data');
    Route::resource('produk', ProdukController::class);

    //route untuk menuju menu pelanggan
    Route::get('pelanggan/data', [PelangganController::class, 'data'])->name('pelanggan.data');
    Route::resource('pelanggan', PelangganController::class);

    //route untuk menuju menu penjualan
    Route::get('penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
    Route::get('penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::delete('penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

    //route untuk menuju menu penjualan_detail dan transaksi
    Route::get('transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
    Route::post('transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
    Route::get('transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');

    //route untuk menuju pembuatan nota kecil
    Route::get('/transaksi/nota-kecil',[PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');

    Route::get('transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
    Route::get('transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
    Route::resource('transaksi', PenjualanDetailController::class)
        ->except('create', 'show', 'edit');
});