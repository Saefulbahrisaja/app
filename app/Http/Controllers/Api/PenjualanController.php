<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Inventori;
use App\Models\CashFlow;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'total' => 'required|numeric|min:1',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:inventori,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $penjualan = Penjualan::create([
                'tanggal' => $request->tanggal,
                'total' => $request->total,
            ]);

            $totalModal = 0;
            $totalLaba  = 0;

            foreach ($request->items as $item) {
                // Simpan detail penjualan
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id'    => $item['barang_id'],
                    'jumlah'       => $item['jumlah'],
                    'harga'        => $item['harga'],
                ]);

                // Ambil produk & cek stok
                $produk = Inventori::findOrFail($item['barang_id']);
                if ($produk->stok < $item['jumlah']) {
                    throw new \Exception("Stok tidak mencukupi untuk produk: {$produk->nama_barang}");
                }

                // Kurangi stok
                $produk->stok -= $item['jumlah'];
                $produk->save();

                // Hitung modal & laba
                $modalPerItem = ($produk->harga_satuan ?? 0) * $item['jumlah']; // default 0 kalau null
                $pendapatanPerItem = $item['harga'] * $item['jumlah'];
                $labaPerItem = $pendapatanPerItem - $modalPerItem;

                $totalModal += $modalPerItem;
                $totalLaba  += $labaPerItem;
            }

            // Simpan ke cash_flow - modal kembali
            CashFlow::create([
                'user_id'    => 1, // auth()->id() jika pakai login
                'type'       => 'masuk',
                'kategori'   => 'Modal Kembali',
                'jumlah'     => $totalModal,
                'keterangan' => "Pengembalian modal dari penjualan #{$penjualan->id}",
                'tanggal'    => $request->tanggal,
                'ref_type'   => 'penjualan',
                'ref_id'     => $penjualan->id
            ]);

            // Simpan ke cash_flow - laba bersih
            CashFlow::create([
                'user_id'    => 1, // auth()->id()
                'type'       => 'masuk',
                'kategori'   => 'Laba Bersih',
                'jumlah'     => $totalLaba,
                'keterangan' => "Laba bersih dari penjualan #{$penjualan->id}",
                'tanggal'    => $request->tanggal,
                'ref_type'   => 'penjualan',
                'ref_id'     => $penjualan->id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan dan kas masuk tercatat'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
