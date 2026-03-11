@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 py-6 px-4 transition-colors duration-500">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 border-b border-gray-100 dark:border-slate-800 pb-10 transition-colors">
        <div>
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white tracking-tighter uppercase italic">
                {{ Auth::user()->role == 'admin' ? 'Manajemen' : 'Jejak' }} <span class="text-blue-600">{{ Auth::user()->role == 'admin' ? 'Sirkulasi' : 'Literasi' }}</span>
            </h2>
            <p class="text-gray-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-[0.3em] mt-3 border-l-4 border-blue-600 pl-4">
                {{ Auth::user()->role == 'admin' ? 'Otorisasi & Pantau Aktivitas Peminjaman' : 'Riwayat perjalanan membaca Anda' }}
            </p>
        </div>
        
        @if(Auth::user()->role != 'admin')
        <a href="{{ route('daftar_buku.index') }}" class="group px-6 py-3 bg-slate-900 dark:bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:shadow-xl hover:shadow-blue-500/20 transition-all flex items-center gap-2">
            <i class="ph-bold ph-magnifying-glass group-hover:rotate-12 transition-transform"></i>
            Cari Buku Lagi
        </a>
        @endif
    </div>

    {{-- Content Area --}}
    @if(Auth::user()->role == 'admin')
        {{-- View Admin --}}
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 overflow-hidden shadow-2xl shadow-gray-200/50 dark:shadow-none transition-colors">
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[9px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-slate-500 border-b border-gray-50 dark:border-slate-800">
                            <th class="px-8 py-6">Informasi Buku</th>
                            <th class="px-8 py-6">Peminjam</th>
                            <th class="px-8 py-6 text-center">Status</th>
                            <th class="px-8 py-6">Denda</th>
                            <th class="px-8 py-6">Tenggat</th>
                            <th class="px-8 py-6 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                        @forelse($transactions as $trx)
                        @php
                            if ($trx->status == 'kembali') {
                                $dendaTampil = $trx->denda;
                            } else {
                                $dendaTampil = 0;
                                $tglTenggat = \Carbon\Carbon::parse($trx->tanggal_kembali);
                                if (now()->greaterThan($tglTenggat)) {
                                    $hariTerlambat = now()->diffInDays($tglTenggat);
                                    $dendaTampil = $hariTerlambat * ($setting->denda_per_hari ?? 1000);
                                }
                            }
                        @endphp
                        <tr class="group hover:bg-blue-50/30 dark:hover:bg-blue-900/5 transition-all duration-300">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-16 bg-gray-100 dark:bg-slate-800 rounded-xl overflow-hidden shadow-sm shrink-0 group-hover:scale-110 transition-transform duration-500">
                                        <img src="{{ asset('storage/' . $trx->book->cover) }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 dark:text-white text-xs uppercase tracking-tight leading-tight max-w-[150px]">{{ $trx->book->judul }}</p>
                                        <p class="text-[8px] text-blue-500 dark:text-blue-400 font-black uppercase tracking-[0.2em] mt-2 italic">{{ $trx->book->kategori }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-900 dark:bg-slate-800 flex items-center justify-center text-[10px] font-black text-white uppercase shadow-sm group-hover:-rotate-3 transition-transform">
                                        {{ substr($trx->user->name, 0, 2) }}
                                    </div>
                                    <span class="text-xs font-black text-gray-700 dark:text-slate-300 uppercase tracking-tighter">{{ $trx->user->name }}</span>
                                </div>
                            </td>

                            <td class="px-8 py-5 text-center">
                                <span class="px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest inline-flex items-center gap-2 border transition-all
                                    {{ $trx->status == 'menunggu' ? 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:border-amber-800/30' : 
                                        ($trx->status == 'menunggu_kembali' ? 'bg-indigo-50 text-indigo-600 border-indigo-100 dark:bg-indigo-900/20 dark:border-indigo-800/30 animate-pulse' : 
                                        ($trx->status == 'dipinjam' ? 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-900/20 dark:border-blue-800/30' : 
                                        'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-800/30')) }}">
                                    ● {{ str_replace('_', ' ', $trx->status) }}
                                </span>
                            </td>

                            <td class="px-8 py-5">
                                @if($dendaTampil > 0)
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-black {{ $trx->status == 'kembali' ? 'text-emerald-600' : 'text-red-600 dark:text-red-400' }} tracking-tighter">
                                            Rp {{ number_format($dendaTampil, 0, ',', '.') }}
                                        </span>
                                        @if($trx->status != 'kembali')
                                            <span class="text-[7px] text-gray-400 dark:text-slate-500 font-black uppercase italic tracking-widest animate-pulse">Incurring...</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[9px] font-black text-gray-300 dark:text-slate-700 uppercase italic tracking-widest">Nihil</span>
                                @endif
                            </td>

                            <td class="px-8 py-5 text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-tighter">
                                {{ $trx->tanggal_kembali ? \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d M, Y') : '---' }}
                            </td>

                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end items-center">
                                    @if($trx->status == 'menunggu')
                                        <form action="{{ route('transaksi.approve', $trx->id) }}" method="POST">
                                            @csrf
                                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-500/20">
                                                Approve
                                            </button>
                                        </form>
                                    @elseif($trx->status == 'menunggu_kembali' || $trx->status == 'dipinjam')
                                        <form action="{{ route('transaksi.return', $trx->id) }}" method="POST">
                                            @csrf
                                            <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                                                Konfirmasi
                                            </button>
                                        </form>
                                    @else
                                        <span class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center">
                                            <i class="ph-bold ph-check"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-8 py-32 text-center text-gray-300 dark:text-slate-700 uppercase text-[11px] font-black tracking-[0.5em] italic">No Transactions Found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        {{-- View Siswa --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($transactions as $trx)
            @php
                if ($trx->status == 'kembali') {
                    $dendaSiswa = $trx->denda;
                } else {
                    $dendaSiswa = 0;
                    $tglK = \Carbon\Carbon::parse($trx->tanggal_kembali);
                    if (now()->greaterThan($tglK)) {
                        $hariTerlambat = now()->diffInDays($tglK);
                        $dendaSiswa = $hariTerlambat * ($setting->denda_per_hari ?? 1000);
                    }
                }
            @endphp
            <div class="group bg-white dark:bg-slate-900 p-8 rounded-[3rem] border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                {{-- Background Pattern Ikon --}}
                <div class="absolute -right-6 -top-6 opacity-[0.03] dark:opacity-[0.07] group-hover:rotate-12 transition-transform duration-700">
                    <i class="ph-fill ph-book-bookmark text-[150px] dark:text-white"></i>
                </div>

                <div class="flex gap-6 relative z-10">
                    <div class="w-24 h-36 bg-gray-100 dark:bg-slate-800 rounded-[1.8rem] overflow-hidden shrink-0 shadow-2xl group-hover:scale-105 transition-transform duration-500">
                        <img src="{{ asset('storage/' . $trx->book->cover) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    </div>
                    <div class="flex flex-col justify-between py-2">
                        <div>
                            <span class="text-[8px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.3em] block mb-2">{{ $trx->book->kategori ?? 'Umum' }}</span>
                            <h4 class="text-base font-black text-gray-900 dark:text-white leading-tight uppercase tracking-tighter line-clamp-2 italic">
                                {{ $trx->book->judul }}
                            </h4>
                            
                            @if($dendaSiswa > 0)
                            <div class="mt-3 inline-flex flex-col bg-red-50 dark:bg-red-900/20 px-3 py-1 rounded-xl border border-red-100 dark:border-red-800/30">
                                <span class="text-[11px] font-black {{ $trx->status == 'kembali' ? 'text-emerald-500' : 'text-red-600 dark:text-red-400' }} tracking-tighter leading-none">
                                    Rp {{ number_format($dendaSiswa, 0, ',', '.') }}
                                </span>
                                @if($trx->status != 'kembali')
                                    <span class="text-[6px] text-red-400 font-black uppercase mt-0.5 tracking-widest">Denda Berjalan</span>
                                @endif
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest">
                            <div class="w-2 h-2 rounded-full shadow-sm
                                {{ $trx->status == 'menunggu' ? 'bg-amber-400' : 
                                    ($trx->status == 'menunggu_kembali' ? 'bg-indigo-400 animate-pulse' : 
                                    ($trx->status == 'dipinjam' ? 'bg-blue-500' : 'bg-emerald-500')) }}"></div>
                            <span class="text-gray-500 dark:text-slate-400">{{ str_replace('_', ' ', $trx->status) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-50 dark:border-slate-800 flex justify-between items-center relative z-10">
                    <div class="flex flex-col">
                        <span class="text-[7px] text-gray-400 dark:text-slate-600 uppercase tracking-[0.2em] mb-1 font-black">Tenggat</span>
                        <span class="text-xs font-black {{ \Carbon\Carbon::parse($trx->tanggal_kembali)->isPast() && $trx->status != 'kembali' ? 'text-red-500' : 'text-gray-900 dark:text-white' }}">
                            {{ $trx->tanggal_kembali ? \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d/m/Y') : '--/--/--' }}
                        </span>
                    </div>
                    
                    @if($trx->status == 'dipinjam')
                        <form action="{{ route('transaksi.ajukan_kembali', $trx->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-3 bg-slate-900 dark:bg-indigo-600 text-white rounded-2xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all flex items-center gap-2 group/btn shadow-xl shadow-gray-200 dark:shadow-none">
                                <i class="ph-bold ph-arrow-u-up-left group-hover/btn:-translate-x-1 transition-transform"></i>
                                Kembalikan
                            </button>
                        </form>
                    @elseif($trx->status == 'menunggu_kembali')
                        <div class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl flex items-center gap-2 border border-indigo-100 dark:border-indigo-800/30">
                            <i class="ph-bold ph-hourglass-high text-indigo-500 text-xs animate-spin-slow"></i>
                            <span class="text-[8px] font-black text-indigo-600 uppercase tracking-widest">Verifikasi</span>
                        </div>
                    @else
                        <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-900/20 rounded-full flex items-center justify-center text-emerald-500 border border-emerald-100 dark:border-emerald-800/30">
                            <i class="ph-bold ph-check text-sm"></i>
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full py-32 flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-800/20 rounded-[4rem] border-4 border-dashed border-gray-100 dark:border-slate-800">
                <i class="ph-bold ph-folder-open text-6xl text-gray-200 dark:text-slate-800 mb-4"></i>
                <p class="text-gray-400 text-[11px] font-black uppercase tracking-[0.5em] italic">Belum ada jejak membaca</p>
            </div>
            @endforelse
        </div>
    @endif
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .animate-spin-slow { animation: spin 3s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endsection