@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 py-6 transition-colors duration-500">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-gray-100 dark:border-slate-800 pb-8 transition-colors gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight italic uppercase">
                Kelola <span class="text-blue-600">Pengembalian</span>
            </h2>
            <p class="text-gray-400 dark:text-slate-500 text-sm font-medium mt-1">Daftar buku aktif yang sedang dipinjam oleh siswa.</p>
        </div>
        
        {{-- Statistik Ringkas --}}
        <div class="flex gap-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 px-4 py-2 rounded-2xl border border-blue-100 dark:border-blue-800/30">
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Total Pinjam</p>
                <p class="text-xl font-black text-blue-700 dark:text-blue-400 leading-none mt-1">{{ $transactions->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 overflow-hidden shadow-2xl shadow-gray-200/50 dark:shadow-none transition-all">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead class="bg-gray-50/50 dark:bg-slate-800/50 transition-colors border-b border-gray-100 dark:border-slate-800">
                    <tr class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500">
                        <th class="px-8 py-5">Buku & Kategori</th>
                        <th class="px-8 py-5">Identitas Siswa</th>
                        <th class="px-8 py-5 text-center">Status</th>
                        <th class="px-8 py-5">Estimasi Denda</th>
                        <th class="px-8 py-5">Deadline</th>
                        <th class="px-8 py-5 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800 transition-colors">
                    @forelse($transactions as $trx)
                    <tr class="group hover:bg-blue-50/30 dark:hover:bg-blue-900/5 transition-all duration-300">
                        {{-- Info Buku --}}
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-20 bg-gray-100 dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm shrink-0 group-hover:scale-110 group-hover:rotate-2 transition-all duration-500">
                                    <img src="{{ asset('storage/' . $trx->book->cover) }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-black text-gray-900 dark:text-white text-xs uppercase tracking-tight leading-tight max-w-[180px]">{{ $trx->book->judul }}</p>
                                    <span class="inline-block px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-500 text-[8px] font-black uppercase tracking-widest mt-2 rounded-md">
                                        {{ $trx->book->kategori }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Info Siswa --}}
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-[11px] font-black text-white shadow-lg transition-transform group-hover:-translate-y-1">
                                    {{ substr($trx->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="text-xs font-black text-gray-800 dark:text-slate-200 uppercase tracking-tight">{{ $trx->user->name }}</p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter italic">ID: #{{ $trx->user->id }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-8 py-6 text-center">
                            <span class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest inline-flex items-center gap-2 transition-all
                                {{ $trx->status == 'dipinjam' ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-800/30' : 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' }}">
                                <span class="w-2 h-2 rounded-full bg-current {{ $trx->status == 'dipinjam' ? 'animate-pulse' : '' }}"></span>
                                {{ $trx->status }}
                            </span>
                        </td>

                        {{-- Denda --}}
                        <td class="px-8 py-6">
                            @if($trx->denda_otomatis > 0)
                                <div class="bg-red-50 dark:bg-red-900/20 px-3 py-1.5 rounded-xl border border-red-100 dark:border-red-800/30 inline-block">
                                    <p class="text-red-600 dark:text-red-400 font-black text-xs leading-none">
                                        Rp {{ number_format($trx->denda_otomatis, 0, ',', '.') }}
                                    </p>
                                    <span class="text-[7px] text-red-500 font-black uppercase tracking-[0.2em]">Penalti Aktif</span>
                                </div>
                            @else
                                <span class="text-[10px] text-gray-300 dark:text-slate-700 font-bold italic uppercase tracking-widest">Aman</span>
                            @endif
                        </td>

                        {{-- Batas Kembali --}}
                        <td class="px-8 py-6">
                            @php
                                $isOverdue = \Carbon\Carbon::parse($trx->tanggal_kembali)->isPast() && $trx->status == 'dipinjam';
                            @endphp
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black transition-colors {{ $isOverdue ? 'text-red-600 dark:text-red-400 animate-pulse' : 'text-gray-900 dark:text-white' }}">
                                    {{ $trx->tanggal_kembali ? \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d M, Y') : '---' }}
                                </span>
                                @if($isOverdue)
                                    <span class="text-[7px] text-red-500 font-black uppercase tracking-widest mt-0.5">Sudah Melewati Batas</span>
                                @else
                                    <span class="text-[7px] text-gray-400 font-black uppercase tracking-widest mt-0.5">Waktu Tersisa</span>
                                @endif
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-8 py-6 text-right">
                            <form action="{{ route('transaksi.return', $trx->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                @csrf
                                <button class="group/btn relative overflow-hidden bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-6 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest hover:shadow-xl hover:shadow-emerald-500/20 transition-all flex items-center gap-2 ml-auto">
                                    <i class="ph-bold ph-arrow-u-up-left text-xs group-hover/btn:-translate-x-1 transition-transform"></i>
                                    <span>Kembalikan Buku</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-32">
                            <div class="flex flex-col items-center justify-center text-center">
                                <i class="ph-bold ph-books text-6xl text-gray-200 dark:text-slate-800 mb-4"></i>
                                <p class="text-gray-400 dark:text-slate-600 text-[10px] font-black uppercase italic tracking-[0.3em]">Tidak ada buku yang sedang dipinjam</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection