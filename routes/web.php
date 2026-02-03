<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
use App\Models\book;

// --- HALAMAN GUEST (Bisa diakses tanpa login) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Alur "Daftar Anggota" di Flowmap jika Anggota = False
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- HALAMAN PROTECTED (Wajib Login) ---
    Route::middleware(['auth'])->group(function () {
        Route::get('/', function () {
            $books = Book::all(); // Mengambil semua data buku dari database
            return view('home', compact('books')); // Mengirim variabel $books ke view
        })->name('home');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Fitur CRUD yang diminta soal
    Route::get('/kelola-buku', [BookController::class, 'index'])->name('buku.index');
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index');
});