@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="min-h-screen relative" x-data="{ showModal: {{ session('success') || session('error') ? 'true' : 'false' }} }">
    
    {{-- MODAL NOTIFIKASI (Tengah - Menggantikan Toast) --}}
    <template x-if="showModal">
        <div class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" x-cloak>
            <div @click.away="showModal = false" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-10"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-[3rem] p-12 text-center shadow-2xl border border-blue-500/10 dark:border-slate-800">
                
                {{-- Icon Status --}}
                @if(session('success'))
                    <div class="w-20 h-20 bg-blue-50 dark:bg-blue-900/30 text-blue-600 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <i class="ph-fill ph-check-circle text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-2 italic">Berhasil</h3>
                @else
                    <div class="w-20 h-20 bg-rose-50 dark:bg-rose-900/30 text-rose-600 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <i class="ph-fill ph-warning-circle text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-2 italic">Peringatan</h3>
                @endif
                
                <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-10 leading-relaxed uppercase tracking-tight">
                    {{ session('success') ?? session('error') }}
                </p>
                
                <button @click="showModal = false" class="w-full py-4 bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                    Tutup
                </button>
            </div>
        </div>
    </template>

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
    <div class="mt-10">
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
                                <th class="px-8 py-6 text-right">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                            @forelse($transactions as $trx)
                            @php
                                if ($trx->status == 'kembali') {
                                    $dendaTampil = $trx->denda;
                                } else {
                                    $dendaTampil = $trx->denda_otomatis ?? 0;
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
                                        <div class="w-8 h-8 rounded-lg bg-slate-900 dark:bg-slate-800 flex items-center justify-center text-[10px] font-black text-white uppercase">
                                            {{ substr($trx->user->name, 0, 2) }}
                                        </div>
                                        <span class="text-xs font-black text-gray-700 dark:text-slate-300 uppercase tracking-tighter">{{ $trx->user->name }}</span>
                                    </div>
                                </td>

                                <td class="px-8 py-5 text-center">
                                    <span class="px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest inline-flex items-center gap-2 border transition-all
                                        {{ $trx->status == 'menunggu' ? 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20' : 
                                           ($trx->status == 'ditolak' ? 'bg-red-50 text-red-600 border-red-100 dark:bg-red-900/20' :
                                           ($trx->status == 'dipinjam' ? 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-900/20' : 
                                           'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20')) }}">
                                        ● {{ str_replace('_', ' ', $trx->status) }}
                                    </span>
                                </td>

                                <td class="px-8 py-5">
                                    @if($dendaTampil > 0)
                                        <span class="text-[11px] font-black {{ $trx->status == 'kembali' ? 'text-emerald-600' : 'text-red-600' }}">
                                            Rp {{ number_format($dendaTampil, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-[9px] font-black text-gray-300 dark:text-slate-700 uppercase italic tracking-widest">Nihil</span>
                                    @endif
                                </td>

                                <td class="px-8 py-5 text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-tighter">
                                    {{ $trx->tanggal_kembali ? \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d M, Y') : '---' }}
                                </td>

                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        @if($trx->status == 'menunggu')
                                            <form action="{{ route('transaksi.approve', $trx->id) }}" method="POST">
                                                @csrf
                                                <button class="w-9 h-9 flex items-center justify-center bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20">
                                                    <i class="ph-bold ph-check text-sm"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('transaksi.reject', $trx->id) }}" method="POST" onsubmit="return confirm('Tolak permintaan pinjaman ini?')">
                                                @csrf
                                                <button class="w-9 h-9 flex items-center justify-center bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-red-500/20">
                                                    <i class="ph-bold ph-x text-sm"></i>
                                                </button>
                                            </form>
                                        @elseif($trx->status == 'menunggu_kembali' || $trx->status == 'dipinjam')
                                            <form action="{{ route('transaksi.return', $trx->id) }}" method="POST">
                                                @csrf
                                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-500/20">
                                                    Konfirmasi Kembali
                                                </button>
                                            </form>
                                        @elseif($trx->status == 'ditolak')
                                            <span class="text-[8px] font-black text-red-500 uppercase tracking-[0.2em] border border-red-200 px-3 py-1 rounded-lg">Canceled</span>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center shadow-inner">
                                                <i class="ph-bold ph-check"></i>
                                            </div>
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
                        $dendaSiswa = $trx->denda_otomatis ?? 0;
                    }
                @endphp
                <div class="group bg-white dark:bg-slate-900 p-8 rounded-[3rem] border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-2xl transition-all duration-500 relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 opacity-[0.03] dark:opacity-[0.07]">
                        <i class="ph-fill ph-book-bookmark text-[150px]"></i>
                    </div>

                    <div class="flex gap-6 relative z-10">
                        <div class="w-24 h-36 bg-gray-100 dark:bg-slate-800 rounded-[1.8rem] overflow-hidden shrink-0 shadow-2xl">
                            <img src="{{ asset('storage/' . $trx->book->cover) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col justify-between py-2">
                            <div>
                                <span class="text-[8px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.3em] block mb-2">{{ $trx->book->kategori ?? 'Umum' }}</span>
                                <h4 class="text-base font-black text-gray-900 dark:text-white leading-tight uppercase tracking-tighter line-clamp-2 italic">{{ $trx->book->judul }}</h4>
                                
                                @if($dendaSiswa > 0)
                                <div class="mt-3 inline-flex flex-col bg-red-50 dark:bg-red-900/20 px-3 py-1 rounded-xl">
                                    <span class="text-[11px] font-black text-red-600 dark:text-red-400 tracking-tighter">Rp {{ number_format($dendaSiswa, 0, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest">
                                <div class="w-2 h-2 rounded-full 
                                    {{ $trx->status == 'menunggu' ? 'bg-amber-400' : 
                                       ($trx->status == 'ditolak' ? 'bg-red-500' :
                                       ($trx->status == 'dipinjam' ? 'bg-blue-500' : 'bg-emerald-500')) }}"></div>
                                <span class="text-gray-500 dark:text-slate-400">{{ str_replace('_', ' ', $trx->status) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-50 dark:border-slate-800 flex justify-between items-center relative z-10">
                        <div class="flex flex-col">
                            <span class="text-[7px] text-gray-400 uppercase tracking-[0.2em] mb-1 font-black">Tenggat</span>
                            <span class="text-xs font-black {{ \Carbon\Carbon::parse($trx->tanggal_kembali)->isPast() && $trx->status == 'dipinjam' ? 'text-red-500' : 'text-gray-900 dark:text-white' }}">
                                {{ $trx->tanggal_kembali ? \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d/m/Y') : '--/--/--' }}
                            </span>
                        </div>
                        
                        @if($trx->status == 'dipinjam')
                            <form action="{{ route('transaksi.ajukan_kembali', $trx->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-3 bg-slate-900 dark:bg-indigo-600 text-white rounded-2xl text-[9px] font-black uppercase tracking-widest transition-all shadow-lg active:scale-95">Kembalikan</button>
                            </form>
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
</div>
@endsection