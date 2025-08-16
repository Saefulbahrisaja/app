<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF; // barryvdh/laravel-dompdf

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        // view hanya menampilkan halaman; data diambil via AJAX
        return view('laporan.penjualan_index');
    }

    /**
     * Endpoint AJAX untuk DataTables
     */
    public function data(Request $request)
    {
        $start = $request->start_date ?? date('Y-m-01');
        $end   = $request->end_date   ?? date('Y-m-t');

        // Ambil detail penjualan dengan join penjualan + inventori
        $rows = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->join('inventori', 'detail_penjualan.barang_id', '=', 'inventori.id')
            ->select(
                'penjualan.id as penjualan_id',
                'penjualan.tanggal as tanggal',
                'inventori.nama_barang as produk',
                'detail_penjualan.jumlah',
                'detail_penjualan.harga',
                DB::raw('(detail_penjualan.jumlah * detail_penjualan.harga) as subtotal'),
                'inventori.harga_satuan as modal_per_unit'
            )
            ->whereBetween(DB::raw('DATE(penjualan.tanggal)'), [$start, $end])
            ->orderBy('penjualan.tanggal', 'desc')
            ->get();

        // Hitung ringkasan
        $totalPendapatan = $rows->sum('subtotal');
        $totalModal = $rows->reduce(function ($carry, $r) {
            return $carry + (($r->modal_per_unit ?? 0) * $r->jumlah);
        }, 0);
        $totalLaba  = $totalPendapatan - $totalModal;

        // Siapkan data untuk DataTables
        $data = $rows->map(function ($r) {
            return [
                'penjualan_id' => $r->penjualan_id,
                'tanggal' => date('d M Y', strtotime($r->tanggal)),
                'produk' => $r->produk,
                'jumlah' => $r->jumlah,
                'harga' => (float) $r->harga,
                'subtotal' => (float) $r->subtotal,
                'modal_per_unit' => (float) ($r->modal_per_unit ?? 0),
                'laba_item' => ($r->subtotal - (($r->modal_per_unit ?? 0) * $r->jumlah)),
            ];
        })->toArray();

        return response()->json([
            'data' => $data,
            'summary' => [
                'total_pendapatan' => $totalPendapatan,
                'total_modal' => $totalModal,
                'total_laba' => $totalLaba,
            ],
        ]);
    }

    /**
     * Cetak PDF untuk rentang tanggal
     */
    public function pdf(Request $request)
    {
        $start = $request->start_date ?? date('Y-m-01');
        $end   = $request->end_date   ?? date('Y-m-t');

        $rows = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->join('inventori', 'detail_penjualan.barang_id', '=', 'inventori.id')
            ->select(
                'penjualan.id as penjualan_id',
                'penjualan.tanggal as tanggal',
                'inventori.nama_barang as produk',
                'detail_penjualan.jumlah',
                'detail_penjualan.harga',
                DB::raw('(detail_penjualan.jumlah * detail_penjualan.harga) as subtotal'),
                'inventori.harga_satuan as modal_per_unit'
            )
            ->whereBetween(DB::raw('DATE(penjualan.tanggal)'), [$start, $end])
            ->orderBy('penjualan.tanggal', 'desc')
            ->get();

        $totalPendapatan = $rows->sum('subtotal');
        $totalModal = $rows->reduce(function ($carry, $r) {
            return $carry + (($r->modal_per_unit ?? 0) * $r->jumlah);
        }, 0);
        $totalLaba  = $totalPendapatan - $totalModal;

        $data = [
            'start' => $start,
            'end' => $end,
            'rows' => $rows,
            'totalPendapatan' => $totalPendapatan,
            'totalModal' => $totalModal,
            'totalLaba' => $totalLaba,
        ];

        $pdf = PDF::loadView('laporan.penjualan_pdf', $data)->setPaper('a4', 'portrait');
        $fileName = "Laporan_Penjualan_{$start}_sd_{$end}.pdf";
        return $pdf->download($fileName);
    }
}
