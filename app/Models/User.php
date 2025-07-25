<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Model
{
    use HasFactory;
    protected $table = 'user';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Tambahkan kolom role jika ada    
    ];
}
