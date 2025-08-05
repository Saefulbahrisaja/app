<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventori;
use App\Models\Production;
use App\Models\Sale;
use App\Models\Kas;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function create()
    {
        $productions = Production::select('produk', 'harga_jual')
            ->get()
            ->map(function ($row) {
                $row->stok = Inventori::where('nama_barang', $row->produk)
                            ->where('jenis', 'barang_jadi')
                            ->value('stok') ?? 0;

                // Ambil HPP dari produksi terakhir produk tersebut
                $row->hpp = Production::where('produk', $row->produk)
                            ->orderByDesc('tanggal')
                            ->value(DB::raw('(total_biaya_bahan + biaya_tenaga_kerja + biaya_overhead) / jumlah_produksi')) ?? 0;

                return $row;
            });

        return view('sales.create', compact('productions'));
    }


    public function store(Request $request)
{
    DB::beginTransaction();
    try {
        foreach ($request->transaksi as $item) {
            $produk = Inventori::where('id', $item['produk'])->first();

            if (!$produk) {
                return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan'], 404);
            }

            if ($produk->stok < $item['jumlah']) {
                return response()->json(['status' => 'error', 'message' => 'Stok tidak cukup untuk produk: ' . $produk->nama_barang], 422);
            }

            // Kurangi stok
            $produk->stok -= $item['jumlah'];
            $produk->save();

            // Simpan ke tabel sales
            Sale::create([
                'produk' => $produk->nama_barang,
                'jumlah' => $item['jumlah'],
                'harga_jual' => $produk->harga,
                'total' => $item['jumlah'] * $item['harga'],
                'tanggal' => now(),
            ]);

            // Tambahkan ke kas
            Kas::create([
                'tanggal' => now(),
                'kategori' => 'penjualan',
                'jumlah' => $item['jumlah'] * $item['harga'],
                'tipe' => 'masuk',
                'keterangan' => 'Penjualan ' . $produk->nama_barang,
            ]);
        }

        DB::commit();
        return response()->json(['status' => 'success', 'message' => 'Transaksi berhasil']);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
}


    
  

   
}
