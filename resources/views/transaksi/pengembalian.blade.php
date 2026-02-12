@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 py-6 transition-colors duration-500">
    <div class="border-b border-gray-100 dark:border-slate-800 pb-6 transition-colors">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Kelola Pengembalian</h2>
        <p class="text-gray-400 dark:text-slate-500 text-sm font-medium">Daftar buku yang sedang dipinjam oleh siswa.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 overflow-hidden shadow-sm transition-all">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 dark:bg-slate-800/50 transition-colors">
                    <tr class="text-[10px] uppercase tracking-widest font-bold text-gray-400 dark:text-slate-500">
                        <th class="px-8 py-4">Buku & Kategori</th>
                        <th class="px-8 py-4">Siswa</th>
                        <th class="px-8 py-4 text-center">Status</th>
                        <th class="px-8 py-4">Batas Kembali</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800 transition-colors">
                    @forelse($transactions as $trx)
                    <tr class="group hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all duration-300">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-16 bg-gray-100 dark:bg-slate-800 rounded-xl overflow-hidden shadow-sm shrink-0 group-hover:scale-105 transition-transform duration-500">
                                    <img src="{{ asset('storage/' . $trx->book->cover) }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-black text-gray-900 dark:text-white text-xs uppercase tracking-tight">{{ $trx->book->judul }}</p>
                                    <p class="text-[9px] text-blue-500 dark:text-blue-400 font-bold uppercase tracking-widest mt-1">{{ $trx->book->kategori }}</p>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-900 dark:bg-blue-600 flex items-center justify-center text-[10px] font-bold text-white uppercase shadow-sm">
                                    {{ substr($trx->user->name, 0, 2) }}
                                </div>
                                <span class="text-xs font-bold text-gray-700 dark:text-slate-300">{{ $trx->user->name }}</span>
                            </div>
                        </td>

                        <td class="px-8 py-5 text-center">
                            <span class="px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-tighter inline-flex items-center gap-2 transition-colors
                                {{ $trx->status == 'menunggu' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : 
                                  ($trx->status == 'dipinjam' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 
                                  'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400') }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                                {{ $trx->status }}
                            </span>
                        </td>

                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black transition-colors {{ \Carbon\Carbon::parse($trx->tanggal_kembali)->isPast() && $trx->status == 'dipinjam' ? 'text-red-500 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ $trx->tanggal_kembali ? \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d M, Y') : '---' }}
                                </span>
                                @if(\Carbon\Carbon::parse($trx->tanggal_kembali)->isPast() && $trx->status == 'dipinjam')
                                    <span class="text-[7px] text-red-400 dark:text-red-500 font-black uppercase tracking-widest">Terlambat!</span>
                                @endif
                            </div>
                        </td>

                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end items-center gap-2">
                                @if($trx->status == 'menunggu')
                                    <form action="{{ route('transaksi.approve', $trx->id) }}" method="POST">
                                        @csrf
                                        <button class="group/btn bg-gray-900 dark:bg-blue-600 text-white px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-blue-600 dark:hover:bg-blue-700 transition-all flex items-center gap-2 shadow-sm">
                                            <i class="ph-bold ph-check text-xs group-hover/btn:scale-110 transition-transform"></i>
                                            Setujui
                                        </button>
                                    </form>
                                @elseif($trx->status == 'dipinjam')
                                    <form action="{{ route('transaksi.return', $trx->id) }}" method="POST">
                                        @csrf
                                        <button class="group/btn bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-900 dark:text-white px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:border-emerald-500 dark:hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all flex items-center gap-2 shadow-sm">
                                            <i class="ph-bold ph-arrow-u-up-left text-xs group-hover/btn:-translate-y-0.5 transition-transform"></i>
                                            Kembalikan
                                        </button>
                                    </form>
                                @else
                                    <div class="flex items-center gap-2 text-emerald-500 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-4 py-2 rounded-xl transition-colors">
                                        <i class="ph-bold ph-seal-check text-sm"></i>
                                        <span class="text-[9px] font-black uppercase tracking-widest">Selesai</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <p class="text-gray-400 dark:text-slate-600 text-xs font-black uppercase italic tracking-widest transition-colors">Tidak ada data peminjaman.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection