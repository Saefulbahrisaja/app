<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\InventoriController;

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
Route::get('/kas', [KasController::class, 'index'])->name('kas.index');
