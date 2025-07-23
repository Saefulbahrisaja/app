<?php
// app/Models/CashFlow.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    protected $table = 'cash_flow'; // Specify the table name if it differs from the model name
    protected $fillable = [
        'user_id', 'type', 'kategori', 'jumlah', 'keterangan', 'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}