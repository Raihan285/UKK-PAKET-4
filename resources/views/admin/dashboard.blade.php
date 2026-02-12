@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f8fafc] dark:bg-slate-950 p-6 transition-colors duration-500">
    <div class="max-w-7xl mx-auto space-y-8">
        
        {{-- Header Tanpa Tombol (Hanya Judul) --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Ringkasan Sistem</h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Data keseluruhan Booktify</p>
            </div>
            {{-- Tombol dihapus karena sudah ada di Navbar/Layout --}}
        </div>

        {{-- Grid Statistik 4 Kolom --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Card: Total Siswa --}}
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 rounded-2xl"><i class="ph-bold ph-users text-xl"></i></div>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $total_siswa }}</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Anggota</p>
            </div>

            {{-- Card: Total Buku --}}
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-2xl"><i class="ph-bold ph-books text-xl"></i></div>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $total_buku }}</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Koleksi Buku</p>
            </div>

            {{-- Card: Dipinjam --}}
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 text-amber-600 rounded-2xl"><i class="ph-bold ph-swap text-xl"></i></div>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $total_dipinjam }}</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sirkulasi Aktif</p>
            </div>

            {{-- Card: Denda --}}
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-rose-50 dark:bg-rose-900/20 text-rose-600 rounded-2xl"><i class="ph-bold ph-coins text-xl"></i></div>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($total_denda, 0, ',', '.') }}</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Akumulasi Denda</p>
            </div>
        </div>

        {{-- Tabel Persetujuan Cepat --}}
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="px-8 py-6 border-b border-gray-50 dark:border-slate-800">
                <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Butuh Persetujuan Peminjaman</h3>
            </div>
            <table class="w-full text-left">
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                    @forelse($pending_approvals as $trx)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-8 py-4">
                            <p class="text-xs font-bold text-gray-900 dark:text-white">{{ $trx->user->name }}</p>
                            <p class="text-[9px] text-gray-400 uppercase">{{ $trx->book->judul }}</p>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <form action="{{ route('transaksi.approve', $trx->id) }}" method="POST">
                                @csrf
                                <button class="px-4 py-2 bg-blue-600 text-white text-[9px] font-black uppercase rounded-xl hover:bg-blue-700 transition-all">Setujui</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-8 py-10 text-center text-gray-400 dark:text-slate-600 text-xs italic font-bold uppercase tracking-widest">
                            Tidak ada antrean persetujuan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection