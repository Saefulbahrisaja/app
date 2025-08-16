<?php

namespace App\Http\Controllers;

use App\Models\Inventori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanInventoriController extends Controller
{
    public function index()
    {
        return view('laporan.inventori');
    }

    public function getTerjual(Request $request)
    {
        $query = DB::table('detail_penjualan')
            ->join('inventori', 'detail_penjualan.barang_id', '=', 'inventori.id')
            ->select(
                'inventori.nama_barang',
                DB::raw('SUM(detail_penjualan.jumlah) as total_terjual')
            )
            ->where('inventori.jenis', 'barang_jadi');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween(DB::raw('DATE(detail_penjualan.created_at)'), [$request->start_date, $request->end_date]);
        }

        $data = $query->groupBy('inventori.nama_barang')->get();
        return response()->json(['data' => $data]);
    }

    public function getStok(Request $request)
    {
        $data = Inventori::where('jenis', 'barang_jadi')->get();
        return response()->json(['data' => $data]);
    }

    public function getBahan(Request $request)
    {
        $query = DB::table('production_detail')
            ->join('inventori', 'production_detail.inventori_id', '=', 'inventori.id')
            ->select(
                'inventori.nama_barang as nama_bahan',
                DB::raw('inventori.stok + SUM(production_detail.jumlah) as stok_awal'),
                DB::raw('SUM(production_detail.jumlah) as jumlah_digunakan'),
                'inventori.stok as sisa_stok',
                'inventori.satuan',
                DB::raw('DATE(production_detail.created_at) as tanggal_penggunaan')
            )
            ->where('inventori.jenis', 'bahan_baku');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween(DB::raw('DATE(production_detail.created_at)'), [$request->start_date, $request->end_date]);
        }

        $data = $query
            ->groupBy('inventori.nama_barang', 'inventori.stok', 'inventori.satuan', 'tanggal_penggunaan')
            ->orderBy('tanggal_penggunaan', 'desc')
            ->get();

        return response()->json(['data' => $data]);
    }
}
