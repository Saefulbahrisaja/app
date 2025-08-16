<?php

namespace App\Http\Controllers;

use App\Models\Inventori;
use App\Models\Production;
use App\Models\ProductionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\CashFlow;


class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
    $start = $request->start_date ?? now()->startOfMonth();
    $end = $request->end_date ?? now()->endOfMonth();

    $productions = Production::with('bahanBaku')
        ->whereBetween('tanggal', [$start, $end])
        ->orderBy('tanggal', 'desc')
        ->get();
    $bahanBaku = Inventori::where('jenis', 'bahan_baku')->get();

    return view('produksi.index', compact('productions', 'bahanBaku','start', 'end'));
    }
    
    public function chartData()
    {
        $productions = Production::selectRaw("
                DATE_FORMAT(tanggal, '%Y-%m') as periode,
                AVG((total_biaya_bahan + biaya_tenaga_kerja + biaya_overhead) / jumlah_produksi) as avg_hpp,
                SUM(total_biaya_bahan + biaya_tenaga_kerja + biaya_overhead) as total_biaya
            ")
            ->groupBy('periode')
            ->orderBy('periode')
            ->get();

        return response()->json($productions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'produk' => 'required|string',
            'jumlah_produksi' => 'required|integer|min:1',
            'bahan_baku' => 'required|array', // array of inventory_id => jumlah
            'bahan_baku.*' => 'required|integer|min:0',
            'biaya_tenaga_kerja' => 'nullable|numeric',
            'biaya_overhead' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($request) {
            $produksi = Production::create([
                'tanggal' => $request->tanggal,
                'produk' => $request->produk,
                'jumlah_produksi' => $request->jumlah_produksi,
                'total_biaya_bahan' => 0, // akan dihitung
                'biaya_tenaga_kerja' => $request->biaya_tenaga_kerja ?? 0,
                'biaya_overhead' => $request->biaya_overhead ?? 0,
            ]);

            $total_bahan = 0;

            foreach ($request->bahan_baku as $inventori_id => $jumlah) {
                $inventory = Inventori::findOrFail($inventori_id);

                if ($inventory->stok < $jumlah) {
                    throw new \Exception("Stok bahan baku {$inventory->nama_barang} tidak mencukupi!");
                }

                // Kurangi stok bahan baku
                $inventory->decrement('stok', $jumlah);

                // Tambahkan ke pivot
                $produksi->bahanBaku()->attach($inventori_id, ['jumlah' => $jumlah]);

                // Hitung total biaya bahan baku
                $total_bahan += $inventory->harga_satuan * $jumlah;
            }

            // Update total biaya bahan baku
            $produksi->update(['total_biaya_bahan' => $total_bahan]);

            // Tambahkan barang jadi ke stok inventaris
            $barangJadi = Inventori::firstOrCreate(
                ['nama_barang' => $request->produk, 'jenis' => 'barang_jadi'],
                ['stok' => 0, 'satuan' => 'pcs']
            );

            $barangJadi->increment('stok', $request->jumlah_produksi);
            
            // ⬇ Tambahkan pengeluaran kas otomatis
            // 🧾 Pengeluaran untuk bahan baku
            if ($total_bahan > 0) {
                CashFlow::create([
                    'user_id' => 1,//auth()->id(),
                    'type' => 'keluar',
                    'kategori' => 'Bahan Baku (' . $request->produk. ' )',
                    'jumlah' => $total_bahan,
                    'keterangan' => 'Biaya bahan baku produksi ' . $request->produk,
                    'tanggal' => $request->tanggal,
                    'ref_type' => Production::class,
                    'ref_id' => $produksi->id,
                ]);
            }

            // 👷‍♂️ Pengeluaran untuk tenaga kerja
            if ($produksi->biaya_tenaga_kerja > 0) {
                CashFlow::create([
                    'user_id' => 1,//auth()->id(),
                    'type' => 'keluar',
                    'kategori' => 'Tenaga Kerja (' . $request->produk. ' )',
                    'jumlah' => $produksi->biaya_tenaga_kerja,
                    'keterangan' => 'Biaya tenaga kerja produksi ' . $request->produk,
                    'tanggal' => $request->tanggal,
                    'ref_type' => Production::class,
                    'ref_id' => $produksi->id,
                ]);
            }

            // 🏭 Pengeluaran untuk overhead
            if ($produksi->biaya_overhead > 0) {
                CashFlow::create([
                    'user_id' => 1,//auth()->id(),
                    'type' => 'keluar',
                    'kategori' => 'Overhead (' . $request->produk . ' )',
                    'jumlah' => $produksi->biaya_overhead,
                    'keterangan' => 'Biaya overhead produksi ' . $request->produk,
                    'tanggal' => $request->tanggal,
                    'ref_type' => Production::class,
                    'ref_id' => $produksi->id,
                ]);
            }



            // Hitung HPP per unit
            $totalProduksi = $request->jumlah_produksi;
            $totalBiaya = $total_bahan + ($request->biaya_tenaga_kerja ?? 0) + ($request->biaya_overhead ?? 0);
            $hpp = $totalProduksi > 0 ? $totalBiaya / $totalProduksi : 0;

            $produksi->hpp_per_unit = $hpp;
            $produksi->save();

            // Hitung harga jual dengan markup 10% dari HPP
            $hargaJual = $hpp * 1.10;
            $pembulatanharga=round($hargaJual); 
            
            // Update harga_satuan di tabel inventori untuk barang jadi
            $barangJadi->update(['harga_satuan' => $pembulatanharga]);
            
            // Simpan harga jual di tabel production
            $produksi->update(['harga_jual' => $pembulatanharga]);

    
        });



        return redirect()->route('production.index')->with('success', 'Produksi berhasil disimpan dan stok diperbarui.');
    }

        

}
