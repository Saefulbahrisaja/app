<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class LaporanLabaRugiController extends Controller
{
    public function index()
    {
        return view('laporan.laba_rugi');
    }

    public function getData(Request $request)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date   = $request->end_date ?? date('Y-m-t');

        // Hitung pendapatan
        $pendapatan = DB::table('penjualan')
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->sum('total');

        // Hitung HPP
        $hpp = DB::table('production')
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->sum(DB::raw('total_biaya_bahan + biaya_tenaga_kerja + biaya_overhead'));

        $laba_kotor = $pendapatan - $hpp;
        $biaya_operasional = 0; // kalau ada, ambil dari tabel lain
        $laba_bersih = $laba_kotor - $biaya_operasional;

        $data = [[
            'pendapatan' => $pendapatan,
            'hpp' => $hpp,
            'laba_kotor' => $laba_kotor,
            'biaya_operasional' => $biaya_operasional,
            'laba_bersih' => $laba_bersih
        ]];

        return response()->json(['data' => $data]);
    }

    public function cetakPDF(Request $request)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date   = $request->end_date ?? date('Y-m-t');

        $pendapatan = DB::table('penjualan')
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->sum('total');

        $hpp = DB::table('production')
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->sum(DB::raw('total_biaya_bahan + biaya_tenaga_kerja + biaya_overhead'));

        $laba_kotor = $pendapatan - $hpp;
        $biaya_operasional = 0;
        $laba_bersih = $laba_kotor - $biaya_operasional;

        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'pendapatan' => $pendapatan,
            'hpp' => $hpp,
            'laba_kotor' => $laba_kotor,
            'biaya_operasional' => $biaya_operasional,
            'laba_bersih' => $laba_bersih
        ];

        $pdf = PDF::loadView('laporan.laba_rugi_pdf', $data);
        return $pdf->download("Laporan_Laba_Rugi_{$start_date}_sd_{$end_date}.pdf");
    }
}
