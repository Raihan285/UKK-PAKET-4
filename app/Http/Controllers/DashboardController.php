<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // Pastikan ini ada
use App\Models\User;
use App\Models\Book;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $total_siswa = User::where('role', 'siswa')->count();
        $total_buku = Book::count();
        $total_dipinjam = Transaction::where('status', 'dipinjam')->count();
        $total_denda = Transaction::sum('denda');

        // Data Tambahan untuk Admin
        $pending_approvals = Transaction::with(['user', 'book'])->where('status', 'menunggu')->latest()->take(5)->get();
        $recent_returns = Transaction::with(['user', 'book'])->where('status', 'dikembalikan')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'total_siswa', 'total_buku', 'total_dipinjam', 'total_denda', 'pending_approvals', 'recent_returns'
        ));
    }
}