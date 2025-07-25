<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'tanggal',
        'produk',
        'jumlah',
        'harga_satuan',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
