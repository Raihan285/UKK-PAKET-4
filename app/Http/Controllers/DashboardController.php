<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\Setting; // Tambahkan ini
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Dasar
        $total_siswa = User::where('role', 'siswa')->count();
        $total_buku = Book::sum('stok'); // Mengambil total stok fisik buku
        $total_dipinjam = Transaction::where('status', 'dipinjam')->count();

        // 2. LOGIKA DENDA OTOMATIS (Agar sinkron dengan halaman pengembalian)
        $setting = Setting::first();
        $dendaPerHari = $setting ? $setting->denda_per_hari : 1000;

        // A. Denda yang sudah fix (Buku sudah dikembalikan)
        // Kita gunakan abs() untuk jaga-jaga jika ada data negatif di DB
        $dendaSudahSelesai = Transaction::where('status', 'kembali')->sum('denda');

        // B. Denda yang sedang berjalan (Buku masih dipinjam tapi sudah telat)
        $dendaBerjalan = Transaction::whereIn('status', ['dipinjam', 'menunggu_kembali'])
            ->get()
            ->sum(function ($trx) use ($dendaPerHari) {
                $tglDeadline = Carbon::parse($trx->tanggal_kembali)->startOfDay();
                $tglSekarang = Carbon::now()->startOfDay();
                
                if ($tglSekarang->gt($tglDeadline)) {
                    return $tglSekarang->diffInDays($tglDeadline) * $dendaPerHari;
                }
                return 0;
            });

        // Total Akumulasi Denda
        $total_denda = abs($dendaSudahSelesai + $dendaBerjalan);

        // 3. Data Tabel
        $pending_approvals = Transaction::with(['user', 'book'])
            ->where('status', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        $recent_returns = Transaction::with(['user', 'book'])
            ->where('status', 'kembali') // Sesuaikan status dengan database Anda ('kembali')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'total_siswa', 'total_buku', 'total_dipinjam', 'total_denda', 'pending_approvals', 'recent_returns'
        ));
    }
}