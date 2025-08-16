<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = ['tanggal', 'total'];

    public function detailItems()
    {
        return $this->hasMany(DetailPenjualan::class);
    }
}
