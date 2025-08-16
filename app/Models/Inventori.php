<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventori extends Model
{
    use HasFactory;
    protected $table = 'inventori'; // Specify the table name if it differs from the model name
    protected $fillable = [
        'nama_barang', 'jenis', 'stok', 'satuan', 'harga_satuan'
    ];

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class);
    }
}
