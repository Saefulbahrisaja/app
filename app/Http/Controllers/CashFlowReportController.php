<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashFlow;
use App\Exports\CashFlowExport;
use Maatwebsite\Excel\Facades\Excel;

class CashFlowReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $query = CashFlow::query();

        if ($start && $end) {
            $query->whereBetween('tanggal', [$start, $end]);
        }

        $data = $query->orderBy('tanggal', 'asc')->get();

        $totalMasuk = $data->where('type', 'masuk')->sum('jumlah');
        $totalKeluar = $data->where('type', 'keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        return view('laporan.kas', compact('data', 'totalMasuk', 'totalKeluar', 'saldo', 'start', 'end'));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new CashFlowExport($request->start_date, $request->end_date),
            'laporan-kas.xlsx'
        );
    }
}
