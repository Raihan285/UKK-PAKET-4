<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Menampilkan riwayat transaksi (Admin/Siswa)
     */
    public function index()
    {
        $setting = Setting::first();
        $dendaPerHari = $setting ? $setting->denda_per_hari : 1000;
        
        $query = Transaction::with(['book', 'user']);
        
        if (Auth::user()->role != 'admin') {
            $query->where('user_id', Auth::id());
        }

        $transactions = $query->latest()->get()->map(function ($trx) use ($dendaPerHari) {
            if ($trx->status == 'dipinjam' || $trx->status == 'menunggu_kembali') {
                $tglDeadline = Carbon::parse($trx->tanggal_kembali)->startOfDay();
                $tglSekarang = Carbon::now()->startOfDay();
                
                if ($tglSekarang->gt($tglDeadline)) {
                    $hariTerlambat = $tglSekarang->diffInDays($tglDeadline);
                    $trx->total_denda_saat_ini = $hariTerlambat * $dendaPerHari;
                } else {
                    $trx->total_denda_saat_ini = 0;
                }
            } else {
                $trx->total_denda_saat_ini = $trx->denda;
            }
            return $trx;
        });

        return view('transaksi.index', compact('transactions', 'setting'));
    }

    /**
     * Menampilkan halaman Kelola Pengembalian khusus Admin
     */
   public function pengembalian()
    {
        $setting = \App\Models\Setting::first();
        $dendaPerHari = $setting ? $setting->denda_per_hari : 1000;

        $transactions = \App\Models\Transaction::with(['book', 'user'])
            ->whereIn('status', ['dipinjam', 'menunggu_kembali'])
            ->get()
            ->map(function ($trx) use ($dendaPerHari) {
                // GUNAKAN parse() agar lebih aman dari error format trailing data
                if ($trx->tanggal_kembali) {
                    $tglDeadline = \Carbon\Carbon::parse($trx->tanggal_kembali)->startOfDay();
                    $tglSekarang = \Carbon\Carbon::now()->startOfDay();
                    
                    if ($tglSekarang->gt($tglDeadline)) {
                        $selisihHari = $tglSekarang->diffInDays($tglDeadline);
                        $trx->denda_otomatis = $selisihHari * $dendaPerHari;
                    } else {
                        $trx->denda_otomatis = 0;
                    }
                } else {
                    $trx->denda_otomatis = 0;
                }
                return $trx;
            });

        return view('transaksi.pengembalian', compact('transactions', 'setting'));
    }

    /**
     * Simpan peminjaman baru (Siswa)
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        Transaction::create([
            'user_id' => Auth::id(), 
            'book_id' => $request->book_id,
            'status' => 'menunggu', 
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Buku berhasil diajukan!');
    }

    /**
     * Setujui peminjaman (Admin)
     */
    public function approve($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->book->stok <= 0) {
            return redirect()->back()->with('error', 'Stok buku habis.');
        }

        $setting = Setting::first();
        $batasHari = $setting ? $setting->batas_hari : 7; 

        $transaction->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays($batasHari), 
        ]);

        $transaction->book->decrement('stok');

        return redirect()->back()->with('success', 'Peminjaman disetujui!');
    }

    /**
     * Proses Pengembalian & Kunci Denda ke Database (Admin)
     */
    public function processReturn($id)
    {
        $transaction = Transaction::findOrFail($id);
        $setting = Setting::first();
        $dendaPerHari = $setting ? $setting->denda_per_hari : 1000;

        $tglDeadline = Carbon::parse($transaction->tanggal_kembali)->startOfDay();
        $tglSekarang = Carbon::now()->startOfDay();
        $totalDenda = 0;

        if ($tglSekarang->gt($tglDeadline)) {
            $hariTerlambat = $tglSekarang->diffInDays($tglDeadline);
            $totalDenda = $hariTerlambat * $dendaPerHari;
        }

        $transaction->update([
            'status' => 'kembali',
            'tanggal_pengembalian' => now(),
            'denda' => $totalDenda, 
        ]);

        $transaction->book->increment('stok');

        return back()->with('success', "Buku kembali! Total denda: Rp " . number_format($totalDenda, 0, ',', '.'));
    }

    /**
     * Fungsi untuk rute pengembalian agar tidak error
     */
    public function returnBook($id)
    {
        return $this->processReturn($id);
    }

    /**
     * Siswa mengajukan pengembalian
     */
    public function ajukanPengembalian($id)
    {
        $transaction = Transaction::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $transaction->update(['status' => 'menunggu_kembali']);

        return redirect()->back()->with('success', 'Permintaan pengembalian terkirim.');
    }

    public function reject($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Pastikan hanya transaksi yang berstatus 'menunggu' yang bisa ditolak
        if ($transaction->status === 'menunggu') {
            $transaction->update([
                'status' => 'ditolak'
            ]);

            return redirect()->back()->with('error', 'Permintaan peminjaman buku telah ditolak.');
        }

        return redirect()->back()->with('error', 'Status transaksi tidak valid untuk ditolak.');
    }
}