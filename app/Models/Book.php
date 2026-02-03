<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi (Mass Assignable)
     * Sesuai dengan tampilan di home.blade.php
     */
    protected $fillable = [
        'title',    
        'author',   
        'category', 
        'cover',    
        'stok',     
    ];
}