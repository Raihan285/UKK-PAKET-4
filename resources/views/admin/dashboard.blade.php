@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    .animate-spin-slow { animation: spin 3s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>

<div x-data="{ showModal: {{ session('success') || session('error') ? 'true' : 'false' }} }">
    {{-- Modal Pop-up --}}
    <template x-if="showModal">
        <div class="fixed inset-0 z-[999] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-xl" x-cloak>
            <div @click.away="showModal = false" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white dark:bg-slate-900 w-full max-w-[420px] rounded-[3.5rem] p-12 text-center shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] border border-white/20 relative overflow-hidden">
                
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-32 {{ session('success') ? 'bg-blue-500/10' : 'bg-rose-500/10' }} blur-[40px] rounded-full"></div>

                @if(session('success'))
                    <div class="relative w-24 h-24 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-blue-500/20">
                        <i class="ph-fill ph-check text-5xl"></i>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-4 italic">BERHASIL</h3>
                @else
                    <div class="relative w-24 h-24 bg-rose-600 text-white rounded-full flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-rose-500/20">
                        <i class="ph-fill ph-x text-5xl"></i>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-4 italic">GAGAL</h3>
                @endif
                
                <p class="text-[12px] font-bold text-gray-400 dark:text-gray-500 mb-10 leading-relaxed uppercase tracking-tight">
                    {{ session('success') ?? session('error') }}
                </p>
                
                <button @click="showModal = false" class="w-full py-5 bg-[#f1f5f9] dark:bg-slate-800 text-gray-500 dark:text-gray-300 text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-600 hover:text-white transition-all duration-300 active:scale-95">
                    TUTUP
                </button>
            </div>
        </div>
    </template>

    <div class="min-h-screen bg-[#f8fafc] dark:bg-slate-950 p-6 transition-colors duration-500">
        <div class="max-w-7xl mx-auto space-y-10">
            {{-- Dashboard Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-gray-100 dark:border-slate-800 pb-8">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">
                        Control <span class="text-blue-600">Center</span>
                    </h1>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-[0.4em] mt-2 flex items-center gap-2">
                        <span class="w-8 h-[2px] bg-blue-600"></span>
                        Statistik Real-time Booktify
                    </p>
                </div>
                <div class="hidden md:block text-right">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Sistem</p>
                    <p class="text-xs font-bold text-green-500 uppercase flex items-center justify-end gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span> Operasional Optimal
                    </p>
                </div>
            </div>

            {{-- Grid Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Total Anggota --}}
                <div class="group bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-500">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 rounded-2xl group-hover:rotate-6 transition-transform">
                            <i class="ph-bold ph-users text-2xl"></i>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter">{{ $total_siswa }}</h3>
                        <p class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Total Anggota</p>
                    </div>
                </div>

                {{-- Koleksi Buku --}}
                <div class="group bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-500">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-2xl group-hover:rotate-6 transition-transform">
                            <i class="ph-bold ph-books text-2xl"></i>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter">{{ $total_buku }}</h3>
                        <p class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Item Literatur</p>
                    </div>
                </div>

                {{-- Sirkulasi Aktif --}}
                <div class="group bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-500">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 text-amber-600 rounded-2xl group-hover:rotate-6 transition-transform">
                            <i class="ph-bold ph-swap text-2xl"></i>
                        </div>
                        <span class="text-[9px] font-black px-3 py-1 bg-amber-100 text-amber-700 rounded-full uppercase italic">Aktif</span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter">{{ $total_dipinjam }}</h3>
                        <p class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Buku Dipinjam</p>
                    </div>
                </div>

                {{-- Akumulasi Denda --}}
                <div class="group bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-500">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-4 bg-rose-50 dark:bg-rose-900/20 text-rose-600 rounded-2xl group-hover:rotate-6 transition-transform">
                            <i class="ph-bold ph-coins text-2xl"></i>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">Rp {{ number_format($total_denda, 0, ',', '.') }}</h3>
                        <p class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Total Denda</p>
                    </div>
                </div>
            </div>

            {{-- Bagian Bawah --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Persetujuan Cepat --}}
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-[3rem] border border-gray-100 dark:border-slate-800 overflow-hidden shadow-sm">
                    <div class="px-10 py-8 border-b border-gray-50 dark:border-slate-800 flex justify-between items-center bg-gray-50/50 dark:bg-slate-800/30">
                        <div>
                            <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-[0.2em]">Queue Persetujuan</h3>
                            <p class="text-[9px] font-bold text-gray-400 uppercase mt-1 tracking-widest">Pending Requests</p>
                        </div>
                        <span class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-black shadow-lg">
                            {{ count($pending_approvals) }}
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                                @forelse($pending_approvals as $trx)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-blue-900/5 transition-colors group">
                                    <td class="px-10 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-gray-500 font-black text-xs">
                                                {{ strtoupper(substr($trx->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight group-hover:text-blue-600 transition-colors">{{ $trx->user->name }}</p>
                                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest italic mt-0.5">{{ $trx->book->judul }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <div class="flex justify-end items-center gap-3">
                                            {{-- Tombol Terima --}}
                                            <form action="{{ route('transaksi.approve', $trx->id) }}" method="POST">
                                                @csrf
                                                <button class="w-10 h-10 flex items-center justify-center bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/10 active:scale-90" title="Terima">
                                                    <i class="ph-bold ph-check text-sm"></i>
                                                </button>
                                            </form>
                                            {{-- Tombol Tolak --}}
                                            <form action="{{ route('transaksi.reject', $trx->id) }}" method="POST">
                                                @csrf
                                                <button class="w-10 h-10 flex items-center justify-center bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-red-500/10 active:scale-90" title="Tolak">
                                                    <i class="ph-bold ph-x text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center opacity-20">
                                            <i class="ph-bold ph-checks text-5xl mb-2 text-blue-600"></i>
                                            <p class="text-[10px] font-black uppercase tracking-[0.3em]">Antrean Kosong</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Info Tambahan dan Shortcut --}}
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[3rem] p-10 text-white shadow-2xl flex flex-col justify-between relative overflow-hidden group">
                    <i class="ph-bold ph-lightning absolute -right-10 -top-10 text-[15rem] text-white/10 group-hover:rotate-12 transition-transform duration-700"></i>
                    <div class="relative z-10">
                        <h4 class="text-2xl font-black leading-tight tracking-tighter uppercase italic">Quick<br>Actions</h4>
                        <div class="mt-8 space-y-3">
                            <a href="{{ route('buku.index') }}" class="flex items-center justify-between p-4 bg-white/10 rounded-2xl hover:bg-white/20 transition-all backdrop-blur-md border border-white/10">
                                <span class="text-[10px] font-black uppercase tracking-widest">Cek Inventaris</span>
                                <i class="ph-bold ph-arrow-right"></i>
                            </a>
                            <a href="{{ route('anggota.index') }}" class="flex items-center justify-between p-4 bg-white/10 rounded-2xl hover:bg-white/20 transition-all backdrop-blur-md border border-white/10">
                                <span class="text-[10px] font-black uppercase tracking-widest">Data Anggota</span>
                                <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="relative z-10 pt-10">
                        <p class="text-[9px] font-bold uppercase tracking-[0.2em] opacity-60 italic">Booktify Control Center v2.0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection