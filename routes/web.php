<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\InventoriController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\KasController;

use App\Http\Controllers\DashboardController;

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
    return view('dashboard');
});

Route::resource('/cash-flows', CashFlowController::class)->only(['index', 'create', 'store']);
Route::resource('/inventories', InventoriController::class)->only(['index', 'create', 'store', 'edit', 'update']);
Route::resource('/production', ProductionController::class)->only(['index', 'create', 'store']);

Route::get('/dashboard/chart', [DashboardController::class, 'chartData'])->name('dashboard.chart');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/kas', [KasController::class, 'index'])->name('kas.index');
