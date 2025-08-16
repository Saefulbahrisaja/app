<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\Production;
use App\Models\Inventori;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard', [
            'kasMasuk' => CashFlow::where('type', 'masuk')->sum('jumlah'),
            'kasKeluar' => CashFlow::where('type', 'keluar')->sum('jumlah'),
            'produksi' => Production::sum('jumlah_produksi'),
            'profit' => Production::sum(DB::raw('(harga_jual - (total_biaya_bahan + biaya_tenaga_kerja + biaya_overhead) / jumlah_produksi) * jumlah_produksi'))
        ]);
    }

    /**
     * Get chart data for dashboard visualization
     */
    public function chartData()
    {
        // Grafik biaya & HPP bulanan
        $productions = Production::selectRaw("
            DATE_FORMAT(tanggal, '%Y-%m') as label,
            SUM(total_biaya_bahan + biaya_tenaga_kerja + biaya_overhead) as total_biaya,
            AVG((total_biaya_bahan + biaya_tenaga_kerja + biaya_overhead) / NULLIF(jumlah_produksi, 0)) as hpp
        ")
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        // Grafik produk: stok, terjual, margin
        $produkData = Production::selectRaw('
            produk,
            AVG(harga_jual) as harga_jual_rata2,
            AVG((total_biaya_bahan + biaya_tenaga_kerja + biaya_overhead) / NULLIF(jumlah_produksi, 0)) as hpp_rata2
        ')
            ->groupBy('produk')
            ->get()
            ->map(function ($row) {
                // Ambil stok akhir
                $stok = Inventori::where('nama_barang', $row->produk)
                    ->where('jenis', 'barang_jadi')
                    ->value('stok') ?? 0;

                // Ambil jumlah terjual dari tabel detail_penjualan
                $barang = Inventori::where('nama_barang', $row->produk)
                    ->where('jenis', 'barang_jadi')
                    ->first();
                
                $terjual = 0;
                if ($barang) {
                    $terjual = DB::table('detail_penjualan')
                        ->where('barang_id', $barang->id)
                        ->sum('jumlah') ?? 0;
                }

                // Hitung margin berdasarkan yang benar-benar terjual
                $harga_jual = $row->harga_jual_rata2 ?? 0;
                $hpp = $row->hpp_rata2 ?? 0;
                $margin = ($harga_jual - $hpp) * $terjual;

                return [
                    'produk' => $row->produk,
                    'margin' => round($margin, 2),
                    'terjual' => $terjual,
                    'stok' => $stok
                ];
            });

        return response()->json([
            'labels' => $productions->pluck('label'),
            'total_biaya' => $productions->pluck('total_biaya'),
            'hpp' => $productions->pluck('hpp'),
            'products' => $produkData->pluck('produk'),
            'margins' => $produkData->pluck('margin'),
            'terjuals' => $produkData->pluck('terjual'),
            'stoks' => $produkData->pluck('stok'),
        ]);
    }
}
