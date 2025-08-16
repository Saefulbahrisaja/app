<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\InventoriController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CashFlowReportController;
use App\Http\Controllers\LaporanInventoriController;
use App\Http\Controllers\LaporanLabaRugiController;
use App\Http\Controllers\LaporanPenjualanController;



Route::get('/', function () {
    return view('dashboard');
});

Route::post('/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');
Route::get('/penjualan', [PenjualanController::class, 'create'])->name('penjualan.create');

Route::resource('/cash-flows', CashFlowController::class)->only(['index', 'create', 'store']);
Route::resource('/inventories', InventoriController::class)->only(['index', 'create', 'store', 'edit', 'update']);
Route::resource('/production', ProductionController::class)->only(['index', 'create', 'store']);

Route::get('/dashboard/chart', [DashboardController::class, 'chartData'])->name('dashboard.chart');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/kas', [KasController::class, 'index'])->name('kas.index');



Route::get('/laporan-kas', [CashFlowReportController::class, 'index'])->name('laporan.kas');
Route::get('/laporan-kas/export', [CashFlowReportController::class, 'export'])->name('laporan.kas.export');

Route::get('/laporan/inventori', [LaporanInventoriController::class, 'index'])->name('laporan.inventori');

Route::get('/laporan/inventori/terjual', [LaporanInventoriController::class, 'getTerjual'])->name('laporan.inventori.terjual');
Route::get('/laporan/inventori/stok', [LaporanInventoriController::class, 'getStok'])->name('laporan.inventori.stok');
Route::get('/laporan/inventori/bahan', [LaporanInventoriController::class, 'getBahan'])->name('laporan.inventori.bahan');

// routes/web.php
Route::get('/laporan-laba-rugi', [LaporanLabaRugiController::class, 'index'])->name('laba_rugi.index');
Route::get('/laporan-laba-rugi/data', [LaporanLabaRugiController::class, 'getData'])->name('laba_rugi.data');
Route::get('/laporan-laba-rugi/pdf', [LaporanLabaRugiController::class, 'cetakPDF'])->name('laba_rugi.pdf');


Route::get('/laporan/penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan.index');
Route::get('/laporan/penjualan/data', [LaporanPenjualanController::class, 'data'])->name('laporan.penjualan.data');
Route::get('/laporan/penjualan/pdf', [LaporanPenjualanController::class, 'pdf'])->name('laporan.penjualan.pdf');

