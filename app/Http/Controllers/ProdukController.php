<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventori;

class ProdukController extends Controller
{
    public function index()
    {
        return response()->json(Inventori::where('jenis', 'barang_jadi')
            ->where('stok', '>', 0)
            ->get());
    }
}
