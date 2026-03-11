<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['batas_hari', 'denda_per_hari', 'daftar_kategori'];

    protected $casts = [
        'daftar_kategori' => 'array',
    ];
}