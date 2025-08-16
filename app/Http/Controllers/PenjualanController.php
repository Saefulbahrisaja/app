<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    // Controller
    public function create()
    {
        $productions = \DB::table('production')
            ->leftJoin('inventori', 'inventori.nama_barang', '=', 'production.produk')
            ->select(
                'production.produk',
                'production.harga_jual',
                'production.hpp_per_unit as hpp',
                'inventori.stok'
            )
            ->get();

        return view('penjualan.create', compact('productions'));
    }

    public function store(Request $request)
    {
        \DB::transaction(function () use ($request) {
            $penjualanId = \DB::table('penjualan')->insertGetId([
                'tanggal' => now(),
                'total' => $request->total,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->transaksi as $item) {
                // Kurangi stok
                \DB::table('inventori')
                    ->where('nama_barang', $item['produk'])
                    ->decrement('stok', $item['jumlah']);

                // Simpan detail
                \DB::table('detail_penjualan')->insert([
                    'penjualan_id' => $penjualanId,
                    'barang_id' => \DB::table('inventori')->where('nama_barang', $item['produk'])->value('id'),
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return response()->json(['status' => 'success']);
    }

}
