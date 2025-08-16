<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        return view('laporan.penjualan_index');
    }

    private function getData($start, $end)
    {
        // Ambil HPP per unit terbaru untuk setiap barang dari tabel production
        // Ambil HPP per unit terbaru untuk setiap produk berdasarkan nama_barang
$latestProduction = DB::table('production as p1')
    ->select('p1.produk', 'p1.hpp_per_unit')
    ->whereRaw('p1.tanggal = (
        SELECT MAX(p2.tanggal)
        FROM production p2
        WHERE p2.produk = p1.produk
    )');

// Query laporan
    $rows = DB::table('detail_penjualan')
        ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
        ->join('inventori', 'detail_penjualan.barang_id', '=', 'inventori.id')
        ->leftJoinSub($latestProduction, 'prod', function ($join) {
            $join->on('inventori.nama_barang', '=', 'prod.produk');
        })
        ->select(
            'penjualan.id as penjualan_id',
            'penjualan.tanggal as tanggal',
            'inventori.nama_barang as produk',
            'detail_penjualan.jumlah',
            'detail_penjualan.harga',
            DB::raw('(detail_penjualan.jumlah * detail_penjualan.harga) as subtotal'),
            DB::raw('COALESCE(prod.hpp_per_unit, 0) as modal_per_unit')
        )
        ->whereBetween('penjualan.tanggal', [$start, $end])
        ->orderBy('penjualan.tanggal', 'desc')
        ->get();

        return $rows;
    }

    public function data(Request $request)
    {
        $start = $request->start_date
            ? Carbon::parse($request->start_date)->format('Y-m-d')
            : now()->startOfMonth()->toDateString();
        $end   = $request->end_date
            ? Carbon::parse($request->end_date)->format('Y-m-d')
            : now()->endOfMonth()->toDateString();

        $rows = $this->getData($start, $end);

        $totalPendapatan = $rows->sum('subtotal');
        $totalModal = $rows->sum(fn($r) => $r->modal_per_unit * $r->jumlah);
        $totalLaba = $totalPendapatan - $totalModal;

        $data = $rows->map(function ($r) {
            return [
                'penjualan_id' => $r->penjualan_id,
                'tanggal' => Carbon::parse($r->tanggal)->format('d M Y'),
                'produk' => $r->produk,
                'jumlah' => $r->jumlah,
                'harga' => (float) $r->harga,
                'subtotal' => (float) $r->subtotal,
                'modal_per_unit' => (float) $r->modal_per_unit,
                'laba_item' => (float) ($r->subtotal - ($r->modal_per_unit * $r->jumlah)),
            ];
        });

        return response()->json([
            'data' => $data,
            'summary' => [
                'total_pendapatan' => $totalPendapatan,
                'total_modal' => $totalModal,
                'total_laba' => $totalLaba,
            ],
        ]);
    }

    public function pdf(Request $request)
    {
        $start = $request->start_date
            ? Carbon::parse($request->start_date)->format('Y-m-d')
            : now()->startOfMonth()->toDateString();
        $end   = $request->end_date
            ? Carbon::parse($request->end_date)->format('Y-m-d')
            : now()->endOfMonth()->toDateString();

        $rows = $this->getData($start, $end);

        $totalPendapatan = $rows->sum('subtotal');
        $totalModal = $rows->sum(fn($r) => $r->modal_per_unit * $r->jumlah);
        $totalLaba = $totalPendapatan - $totalModal;

        $data = [
            'start' => $start,
            'end' => $end,
            'rows' => $rows,
            'totalPendapatan' => $totalPendapatan,
            'totalModal' => $totalModal,
            'totalLaba' => $totalLaba,
        ];

        $pdf = PDF::loadView('laporan.penjualan_pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download("Laporan_Penjualan_{$start}_sd_{$end}.pdf");
    }
}
