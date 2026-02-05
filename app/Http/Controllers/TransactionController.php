<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        // Jika yang login adalah 'siswa', tampilkan hanya miliknya sendiri
        // Jika yang login adalah 'admin', tampilkan semua (opsional, tergantung keinginan Anda)
        
        if (Auth::user()->role == 'admin') {
            $transactions = Transaction::with(['book', 'user'])->latest()->get();
        } else {
            $transactions = Transaction::with(['book'])
                            ->where('user_id', Auth::id()) // Filter berdasarkan akun yang login
                            ->latest()
                            ->get();
        }

        return view('transaksi.index', compact('transactions'));
    }

    public function approve($id)
    {
        $transaction = \App\Models\Transaction::findOrFail($id);
        
        // 1. Ambil batas hari dari setting. Jika tidak ada, default ke 7 hari.
        $setting = \App\Models\Setting::first();
        $batasHari = $setting ? $setting->batas_hari : 7; 

        // 2. Update status dan hitung tanggal kembali secara dinamis
        $transaction->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now(),
            // Tanggal kembali = Hari ini + Batas Hari dari pengaturan
            'tanggal_kembali' => now()->addDays($batasHari), 
        ]);

        // 3. Kurangi stok buku
        $transaction->book->decrement('stok');

        return redirect()->back()->with('success', 'Peminjaman disetujui! Batas kembali adalah ' . $transaction->tanggal_kembali->format('d M Y'));
    }

    // Tambahkan function store ini di dalam TransactionController
    public function store(Request $request)
    {
        // 1. Validasi input agar tidak ada data kosong
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // 2. Simpan pengajuan pinjaman dengan status 'menunggu'
        // Menggunakan Auth::id() lebih stabil untuk mengambil ID user yang login
        \App\Models\Transaction::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(), 
            'book_id' => $request->book_id,
            'status' => 'menunggu', // Sesuai dokumen UKK: perlu persetujuan admin [cite: 33]
        ]);

        // 3. Arahkan kembali ke halaman transaksi dengan pesan sukses
        return redirect()->route('transaksi.index')->with('success', 'Buku berhasil diajukan! Silakan tunggu persetujuan admin.');
    }

    public function pengembalian()
    {
        // Gunakan Auth facade agar lebih stabil
        if (\Illuminate\Support\Facades\Auth::user()->role != 'admin') {
            abort(403);
        }

        $transactions = Transaction::with(['book', 'user'])
            ->where('status', 'dipinjam')
            ->latest()
            ->get();

        // ERROR FIX: Pastikan memanggil folder 'transaksi', bukan 'admin'
        return view('transaksi.pengembalian', compact('transactions'));
    }
    
    public function processReturn($id)
    {
        $transaction = Transaction::findOrFail($id);
        $setting = \App\Models\Setting::first() ?? (object)['denda_per_hari' => 1000];

        $tgl_deadline = \Carbon\Carbon::parse($transaction->tanggal_kembali);
        $tgl_sekarang = now();
        $total_denda = 0;

        // Jika lewat batas waktu, hitung denda
        if ($tgl_sekarang->gt($tgl_deadline)) {
            $selisih_hari = $tgl_sekarang->diffInDays($tgl_deadline);
            $total_denda = $selisih_hari * $setting->denda_per_hari;
        }

        $transaction->update([
            'status' => 'kembali',
            'tanggal_pengembalian' => $tgl_sekarang,
            'denda' => $total_denda, // Pastikan kolom denda sudah ada di tabel transaksi
        ]);

        $transaction->book->increment('stok');

        return back()->with('success', "Buku kembali! Denda: Rp " . number_format($total_denda, 0, ',', '.'));
    }
}