@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-12 py-6 px-4">
    
    {{-- 1. HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 border-b border-gray-100 pb-10">
        <div>
            <h2 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">
                {{ Auth::user()->role == 'admin' ? 'Manajemen Sirkulasi' : 'Jejak Literasi' }}
            </h2>
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.3em] mt-2 border-l-2 border-blue-600 pl-3">
                {{ Auth::user()->role == 'admin' ? 'Otorisasi & Pantau Aktivitas Peminjaman' : 'Riwayat perjalanan membaca Anda' }}
            </p>
        </div>
        
        @if(Auth::user()->role != 'admin')
        <a href="{{ route('daftar_buku.index') }}" class="px-6 py-3 bg-gray-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-gray-200">
            Cari Buku Lagi
        </a>
        @endif
    </div>

    {{-- 2. CONTENT AREA --}}
    @if(Auth::user()->role == 'admin')
        {{-- ADMIN VIEW: ELEGAN TABLE --}}
        <div class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-[9px] uppercase tracking-[0.2em] font-black text-gray-400 border-b border-gray-50">
                            <th class="px-8 py-6">Informasi Buku</th>
                            <th class="px-8 py-6">Peminjam</th>
                            <th class="px-8 py-6 text-center">Status</th>
                            <th class="px-8 py-6">Tenggat</th>
                            <th class="px-8 py-6 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($transactions as $trx)
                        <tr class="group hover:bg-blue-50/30 transition-all duration-300">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-16 bg-gray-100 rounded-xl overflow-hidden shadow-sm shrink-0 group-hover:scale-105 transition-transform duration-500">
                                        <img src="{{ asset('storage/' . $trx->book->cover) }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 text-xs uppercase tracking-tight">{{ $trx->book->judul }}</p>
                                        <p class="text-[9px] text-blue-500 font-bold uppercase tracking-widest mt-1">{{ $trx->book->kategori }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-[10px] font-bold text-white uppercase shadow-sm">
                                        {{ substr($trx->user->name, 0, 2) }}
                                    </div>
                                    <span class="text-xs font-bold text-gray-700">{{ $trx->user->name }}</span>
                                </div>
                            </td>

                            <td class="px-8 py-5 text-center">
                                <span class="px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-tighter inline-flex items-center gap-2
                                    {{ $trx->status == 'menunggu' ? 'bg-amber-100 text-amber-600' : 
                                       ($trx->status == 'menunggu_kembali' ? 'bg-indigo-100 text-indigo-600 animate-pulse' : 
                                       ($trx->status == 'dipinjam' ? 'bg-blue-100 text-blue-600' : 'bg-emerald-100 text-emerald-600')) }}">
                                    ● {{ str_replace('_', ' ', $trx->status) }}
                                </span>
                            </td>

                            <td class="px-8 py-5">
                                <span class="text-[10px] font-bold {{ \Carbon\Carbon::parse($trx->tanggal_kembali)->isPast() && $trx->status == 'dipinjam' ? 'text-red-500' : 'text-gray-400' }}">
                                    {{ $trx->tanggal_kembali ? \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d M, Y') : '---' }}
                                </span>
                            </td>

                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    @if($trx->status == 'menunggu')
                                        <form action="{{ route('transaksi.approve', $trx->id) }}" method="POST">
                                            @csrf
                                            <button class="bg-gray-900 text-white px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all">
                                                Setujui Pinjam
                                            </button>
                                        </form>
                                    @elseif($trx->status == 'menunggu_kembali')
                                        <form action="{{ route('transaksi.konfirmasi_kembali', $trx->id) }}" method="POST">
                                            @csrf
                                            <button class="bg-emerald-500 text-white px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100">
                                                Konfirmasi Kembali
                                            </button>
                                        </form>
                                    @elseif($trx->status == 'dipinjam')
                                        <span class="text-[9px] font-black text-blue-400 uppercase tracking-widest italic">Dalam Peminjaman</span>
                                    @else
                                        <i class="ph-bold ph-check-circle text-emerald-500 text-xl"></i>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-300">
                                    <i class="ph ph-tray text-4xl"></i>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em]">Tidak ada data sirkulasi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        {{-- STUDENT VIEW: MINIMALIST CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($transactions as $trx)
            <div class="group bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                    <i class="ph-fill ph-bookmarks text-[120px] -rotate-12"></i>
                </div>

                <div class="flex gap-6 relative z-10">
                    <div class="w-24 h-32 bg-gray-100 rounded-[1.5rem] overflow-hidden shrink-0 shadow-lg group-hover:scale-105 transition-transform duration-500">
                        <img src="{{ asset('storage/' . $trx->book->cover) }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col justify-between py-1">
                        <div>
                            <span class="text-[8px] font-black text-blue-600 uppercase tracking-[0.2em] block mb-2">{{ $trx->book->kategori ?? 'Umum' }}</span>
                            <h4 class="text-sm font-black text-gray-900 leading-tight mb-1 uppercase tracking-tighter line-clamp-2 group-hover:text-blue-600 transition-colors">
                                {{ $trx->book->judul }}
                            </h4>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight italic">{{ Str::limit($trx->book->penulis, 15) }}</p>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full 
                                {{ $trx->status == 'menunggu' ? 'bg-amber-400' : 
                                   ($trx->status == 'menunggu_kembali' ? 'bg-indigo-400 animate-pulse' : 
                                   ($trx->status == 'dipinjam' ? 'bg-blue-500' : 'bg-emerald-500')) }}"></div>
                            <span class="text-[9px] font-black text-gray-700 uppercase tracking-widest">{{ str_replace('_', ' ', $trx->status) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-gray-50 flex flex-col gap-4 relative z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-[7px] font-black text-gray-300 uppercase tracking-widest">Tenggat Kembali</span>
                            <span class="text-[10px] font-black {{ \Carbon\Carbon::parse($trx->tanggal_kembali)->isPast() && $trx->status == 'dipinjam' ? 'text-red-500' : 'text-gray-900' }}">
                                {{ $trx->tanggal_kembali ? \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d/m/Y') : '-- / -- / --' }}
                            </span>
                        </div>
                        
                        @if($trx->status == 'dipinjam')
                            <form action="{{ route('transaksi.ajukan_kembali', $trx->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-100 transition-all duration-300 flex items-center gap-2 group/btn">
                                    <i class="ph-bold ph-arrow-u-up-left text-[11px] group-hover/btn:-translate-x-1 transition-transform"></i>
                                    Kembalikan
                                </button>
                            </form>
                        @elseif($trx->status == 'menunggu_kembali')
                            <div class="px-3 py-1 bg-indigo-50 rounded-lg">
                                <i class="ph-bold ph-hourglass text-indigo-500 text-xs"></i>
                            </div>
                        @else
                            <div class="px-3 py-1 bg-emerald-50 rounded-lg">
                                <i class="ph-bold ph-check text-emerald-500 text-xs"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            {{-- Bagian empty tetap sama --}}
            @endforelse
        </div>
    @endif
</div>
@endsection