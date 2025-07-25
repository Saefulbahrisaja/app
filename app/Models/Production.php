<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;
    protected $table = 'production';
     protected $fillable = [
        'tanggal', 
        'produk', 
        'jumlah_produksi',
        'total_biaya_bahan',
        'harga_jual',
        'hpp_per_unit', 
        'biaya_tenaga_kerja', 
        'biaya_overhead'
    ];

    public function bahanBaku()
    {
    
        return $this->belongsToMany(Inventori::class, 'production_detail')
                    ->withPivot('jumlah')
                    ->withTimestamps();
    }

    public function getMarginAttribute()
    {
        return $this->harga_jual ? $this->harga_jual - $this->hpp_per_unit : null;
    }

    public function getProfitPercentAttribute()
    {
        return $this->harga_jual && $this->hpp_per_unit
            ? round(($this->margin / $this->hpp_per_unit) * 100, 2)
            : null;
    }
}
