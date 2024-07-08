<?php

use App\Http\Controllers\Api\PermintaanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('permintaan', [PermintaanController::class, 'index']);
// Route::get('permintaan/{id}', [PermintaanController::class, 'show']);
// Route::post('permintaan', [PermintaanController::class, 'store']);
// Route::put('permintaan/{id}', [PermintaanController::class, 'update']);
Route::apiResource('permintaan-produkjadi', PermintaanController::class);