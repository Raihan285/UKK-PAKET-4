<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Menampilkan riwayat transaksi untuk Admin dan Siswa.
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $transactions = Transaction::with(['book', 'user'])->latest()->get();
        } else {
            $transactions = Transaction::with(['book'])
                            ->where('user_id', Auth::id())
                            ->latest()
                            ->get();
        }

        return view('transaksi.index', compact('transactions'));
    }

    /**
     * Menampilkan halaman khusus pengembalian untuk Admin.
     * Solusi untuk error image_a1f782.png
     */
    public function pengembalian()
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }

        // Filter hanya transaksi yang sedang dipinjam atau menunggu konfirmasi kembali
        $transactions = Transaction::with(['book', 'user'])
                        ->whereIn('status', ['dipinjam', 'menunggu_kembali'])
                        ->latest()
                        ->get();

        return view('transaksi.pengembalian', compact('transactions'));
    }

    /**
     * Siswa mengajukan peminjaman buku.
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

        return redirect()->route('transaksi.index')->with('success', 'Buku diajukan! Tunggu persetujuan admin.');
    }

    /**
     * Admin menyetujui peminjaman.
     */
    public function approve($id)
    {
        $transaction = Transaction::findOrFail($id);
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
     * SISWA: Mengajukan pengembalian (status: menunggu_kembali).
     */
    public function ajukanPengembalian($id)
    {
        $transaction = Transaction::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->where('status', 'dipinjam')
                        ->firstOrFail();

        $transaction->update(['status' => 'menunggu_kembali']);

        return redirect()->back()->with('success', 'Permintaan pengembalian dikirim ke Admin.');
    }

    /**
     * ADMIN: Konfirmasi pengembalian buku fisik & hitung denda.
     * Solusi untuk error image_a1ebe3.png (alias returnBook)
     */
    public function processReturn($id)
    {
        $transaction = Transaction::findOrFail($id);
        $setting = Setting::first() ?? (object)['denda_per_hari' => 1000];

        $tgl_deadline = Carbon::parse($transaction->tanggal_kembali);
        $tgl_sekarang = now();
        $total_denda = 0;

        // Hitung selisih hari jika terlambat
        if ($tgl_sekarang->gt($tgl_deadline)) {
            $selisih_hari = $tgl_sekarang->diffInDays($tgl_deadline);
            $total_denda = $selisih_hari * $setting->denda_per_hari;
        }

        $transaction->update([
            'status' => 'kembali',
            'tanggal_pengembalian' => $tgl_sekarang,
            'denda' => $total_denda,
        ]);

        $transaction->book->increment('stok');

        return back()->with('success', "Buku diterima! Denda: Rp " . number_format($total_denda, 0, ',', '.'));
    }

    /**
     * Alias method untuk route 'transaksi.return' agar tidak error
     */
    public function returnBook($id)
    {
        return $this->processReturn($id);
    }
}