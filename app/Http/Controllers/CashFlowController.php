<?php
// app/Http/Controllers/CashFlowController.php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        // Default filter: bulan ini
        $tanggalMulai = $request->start_date ?? now()->startOfMonth();
        $tanggalAkhir = $request->end_date ?? now()->endOfMonth();

        $data = CashFlow::whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalMasuk = $data->where('type', 'masuk')->sum('jumlah');
        $totalKeluar = $data->where('type', 'keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        return view('cash_flows.index', compact('data', 'totalMasuk', 'totalKeluar', 'saldo', 'tanggalMulai', 'tanggalAkhir'));
    }

    public function create()
    {
        return view('cash_flows.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:masuk,keluar',
            'kategori' => 'required',
            'jumlah' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);

        CashFlow::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('cash-flows.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }
}
