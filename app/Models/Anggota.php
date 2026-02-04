<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    // Field ini harus ada agar data bisa disimpan ke database
    protected $fillable = ['nama', 'email', 'telepon', 'alamat'];
}