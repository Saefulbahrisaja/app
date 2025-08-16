<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{

    protected $table = 'detail_penjualan';
    protected $fillable = ['penjualan_id', 'barang_id', 'jumlah', 'harga'];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Inventori::class);
    }
}
