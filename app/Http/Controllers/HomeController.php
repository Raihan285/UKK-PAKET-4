<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book; // Menggunakan Book (Sesuai folder Models Anda)

class HomeController extends Controller
{
public function index(Request $request) {
    $kategori_dipilih = $request->query('kategori');
    $query = \App\Models\Book::query();
    
    if ($kategori_dipilih) {
        $query->where('kategori', $kategori_dipilih);
    }
    
    $books = $query->get();

    // Blok Try-Catch untuk mengantisipasi tabel yang hilang
    try {
        $setting = \App\Models\Setting::first() ?: (object)[
            'batas_hari' => 7, 
            'denda_per_hari' => 1000
        ];
    } catch (\Exception $e) {
        $setting = (object)[
            'batas_hari' => 7, 
            'denda_per_hari' => 1000
        ];
    }

    $total_buku = \App\Models\Book::count();
    $total_anggota = \App\Models\User::where('role', 'siswa')->count();

    return view('home', compact('books', 'setting', 'total_buku', 'total_anggota', 'kategori_dipilih'));
}
}