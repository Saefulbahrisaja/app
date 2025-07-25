<?php
// app/Http/Controllers/InventoryController.php
namespace App\Http\Controllers;

use App\Models\Inventori;
use Illuminate\Http\Request;



class InventoriController extends Controller
{
    public function index()
    {
        $bahanBaku = Inventori::where('jenis', 'bahan_baku')->get();
        $barangJadi = Inventori::where('jenis', 'barang_jadi')->get();

        return view('inventori.index', compact('bahanBaku', 'barangJadi'));
    }

    public function create()
    {
        return view('inventori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'jenis' => 'required|in:bahan_baku,barang_jadi',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string',
            'harga_satuan' => 'nullable|numeric',
        ]);

        Inventori::create($request->all());

        return redirect()->route('inventories.index')->with('success', 'Data inventaris ditambahkan.');
    }

    public function edit(Inventori $inventory)
    {
        return view('inventories.edit', compact('inventory'));
    }

    public function update(Request $request, Inventori $inventory)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string',
            'harga_satuan' => 'nullable|numeric',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventori.index')->with('success', 'Inventaris berhasil diperbarui.');
    }
}
