<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionDetail extends Model
{
    use HasFactory;
    protected $table = 'production_detail';
    protected $fillable = [
        'production_id', 'inventory_id', 'jumlah_pakai'
    ];

    public function bahan()
    {
        return $this->belongsTo(Inventori::class, 'inventori_id');
    }
}
