<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book; // Menggunakan Book (Sesuai folder Models Anda)

class HomeController extends Controller
{
    public function index()
{
    // Mengambil semua data buku untuk ditampilkan di section "Rekomendasi"
    $books = \App\Models\Book::all();
    
    // Tetap kirim statistik jika Anda ingin menaruhnya di bagian paling atas
    $total_buku = $books->count();
    $total_anggota = \App\Models\User::where('role', 'siswa')->count();

    return view('home', compact('books', 'total_buku', 'total_anggota'));
    }
}