<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CashFlowController,
    InventoriController,
    ProductionController,
    PenjualanController,
    DashboardController,
    CashFlowReportController,
    LaporanInventoriController,
    LaporanLabaRugiController,
    LaporanPenjualanController,
    AuthController,
    MarkupSettingController
};

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Protected (wajib login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |----------------------------------------------------------------------
    | ADMIN ONLY: Dashboard + semua modul selain Penjualan
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        // Dashboard (admin)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/dashboard/chart', [DashboardController::class, 'chartData'])->name('dashboard.chart');

        // Alias agar redirect AuthController ke admin.dashboard tetap valid
        Route::get('/admin/dashboard', function () {
            return redirect()->route('dashboard.index');
        })->name('admin.dashboard');

        // Modul selain penjualan
        Route::resource('/cash-flows', CashFlowController::class)->only(['index', 'create', 'store']);
        Route::resource('/inventories', InventoriController::class)->only(['index', 'create', 'store', 'edit', 'update','destroy']);
        Route::resource('/production', ProductionController::class)->only(['index', 'create', 'store']);
       
        //Modul markup
        Route::get('/markup', [MarkupSettingController::class, 'index'])->name('markup.index');
        Route::post('/markup/{id}', [MarkupSettingController::class, 'update'])->name('markup.update');

        // Laporan
        Route::get('/laporan-kas', [CashFlowReportController::class, 'index'])->name('laporan.kas');
        Route::get('/laporan-kas/export', [CashFlowReportController::class, 'export'])->name('laporan.kas.export');

        Route::get('/laporan/inventori', [LaporanInventoriController::class, 'index'])->name('laporan.inventori');
        Route::get('/laporan/inventori/terjual', [LaporanInventoriController::class, 'getTerjual'])->name('laporan.inventori.terjual');
        Route::get('/laporan/inventori/stok', [LaporanInventoriController::class, 'getStok'])->name('laporan.inventori.stok');
        Route::get('/laporan/inventori/bahan', [LaporanInventoriController::class, 'getBahan'])->name('laporan.inventori.bahan');

        Route::get('/laporan-laba-rugi', [LaporanLabaRugiController::class, 'index'])->name('laba_rugi.index');
        Route::get('/laporan-laba-rugi/data', [LaporanLabaRugiController::class, 'getData'])->name('laba_rugi.data');
        Route::get('/laporan-laba-rugi/pdf', [LaporanLabaRugiController::class, 'cetakPDF'])->name('laba_rugi.pdf');

        Route::get('/laporan/penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');
        Route::get('/laporan/penjualan/data', [LaporanPenjualanController::class, 'data'])->name('laporan.penjualan.data');
        Route::get('/laporan/penjualan/pdf', [LaporanPenjualanController::class, 'pdf'])->name('laporan.penjualan.pdf');
    });

    /*
    |----------------------------------------------------------------------
    | KASIR ONLY: Penjualan
    |----------------------------------------------------------------------
    */
    Route::middleware('role:kasir')->group(function () {
        // Alias agar redirect setelah login kasir tidak error
        Route::get('/kasir/dashboard', function () {
            return redirect()->route('penjualan.create');
        })->name('kasir.dashboard');

        Route::get('/penjualan', [PenjualanController::class, 'create'])->name('penjualan.create');
        Route::post('/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');
        Route::post('/simpankas/save', [CashFlowController::class, 'save'])->name('simpankas.save');

    });
});
