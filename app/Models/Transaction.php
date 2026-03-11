<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 
        'book_id', 
        'tanggal_pinjam', 
        'tanggal_kembali', 
        'tanggal_pengembalian', 
        'status', 
        'denda'
    ];

    // Mengubah String Menjadi Date
    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_pengembalian' => 'date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function book() {
        return $this->belongsTo(Book::class);
    }
}