<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\Api\PenjualanController;


Route::get('/produk', [ProdukController::class, 'index']);
Route::post('/penjualan/store', [PenjualanController::class, 'store']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
