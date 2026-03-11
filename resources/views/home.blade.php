@extends('layouts.app')

@section('content')
<div class="max-w-screen-2xl mx-auto space-y-8 md:space-y-12 py-4 md:py-8 px-4 md:px-8 transition-colors duration-500"> 
    
    @auth
        @php
            $peminjamanAktif = Auth::user()->transactions()->where('status', 'dipinjam')->get();
            $totalDendaUser = 0;
            $dendaPerHari = $setting->denda_per_hari ?? 0;
            $batasHari = $setting->batas_hari ?? 7;

            foreach($peminjamanAktif as $t) {
                $tglPinjam = \Carbon\Carbon::parse($t->tanggal_pinjam);
                $jatuhTempo = $tglPinjam->addDays($batasHari);
                $terlambat = now()->diffInDays($jatuhTempo, false);
                if ($terlambat < 0) {
                    $totalDendaUser += abs($terlambat) * $dendaPerHari;
                }
            }
        @endphp

        {{-- Bagian Dashboard --}}
        <div class="space-y-6 md:space-y-8">
            <div class="border-l-4 md:border-l-8 border-blue-600 pl-4 md:pl-6">
                <h2 class="text-2xl md:text-4xl font-black text-gray-900 dark:text-white tracking-tighter uppercase transition-colors">Dashboard Siswa</h2>
                <p class="text-gray-400 dark:text-slate-500 text-[9px] md:text-xs font-bold uppercase tracking-[0.2em] md:tracking-[0.3em] mt-1">Ringkasan aktivitas kamu</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
                {{-- Card Buku Dipinjam --}}
                <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-sm flex items-center space-x-4 md:space-x-6 transition-all hover:shadow-xl">
                    <div class="p-3 md:p-5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl md:rounded-3xl">
                        <i class="ph-bold ph-books text-2xl md:text-4xl"></i>
                    </div>
                    <div>
                        <p class="text-[9px] md:text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-1">Buku Dipinjam</p>
                        <h3 class="text-xl md:text-4xl font-black text-gray-900 dark:text-white">{{ $peminjamanAktif->count() }}</h3>
                    </div>
                </div>

                {{-- Card Denda --}}
                <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-sm flex items-center space-x-4 md:space-x-6 transition-all hover:shadow-xl">
                    <div class="p-3 md:p-5 {{ $totalDendaUser > 0 ? 'bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 animate-pulse' : 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' }} rounded-2xl md:rounded-3xl">
                        <i class="ph-bold ph-hand-coins text-2xl md:text-4xl"></i>
                    </div>
                    <div>
                        <p class="text-[9px] md:text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-1">Tanggungan Denda</p>
                        <h3 class="text-xl md:text-4xl font-black {{ $totalDendaUser > 0 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                            Rp{{ number_format($totalDendaUser, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                {{-- Card Status --}}
                <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-sm flex items-center space-x-4 md:space-x-6 transition-all hover:shadow-xl">
                    <div class="p-3 md:p-5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-2xl md:rounded-3xl">
                        <i class="ph-bold ph-identification-badge text-2xl md:text-4xl"></i>
                    </div>
                    <div>
                        <p class="text-[9px] md:text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-1">Status Anggota</p>
                        <h3 class="text-sm md:text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ Auth::user()->role }}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    {{-- EXPLORATION SECTION --}}
    <div class="space-y-6 md:space-y-10 pt-4 md:pt-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 border-b border-gray-100 dark:border-slate-800 pb-6 md:pb-10 transition-colors">
            <div class="w-full lg:w-auto">
                <h2 class="text-3xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tighter uppercase lg:normal-case">Eksplorasi Buku</h2>
                <p class="text-gray-500 dark:text-slate-400 font-bold text-sm md:text-lg mt-1 md:mt-2">
                    @if(request('kategori'))
                        Kategori <span class="text-blue-600 dark:text-blue-400 uppercase">"{{ request('kategori') }}"</span>
                    @else
                        Temukan ribuan ilmu dalam satu genggaman.
                    @endif
                </p>
            </div>
            
            {{-- Filter Kategori --}}
            <div class="w-full lg:w-auto overflow-x-auto pb-2 lg:pb-0 no-scrollbar">
                <div class="flex flex-nowrap lg:flex-wrap gap-2 md:gap-3">
                    <a href="{{ route('home') }}" 
                       class="whitespace-nowrap px-6 md:px-8 py-3 md:py-4 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black transition-all uppercase tracking-widest {{ !request('kategori') ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500' }}">
                        Semua
                    </a>

                    @php $kategoris = array_filter($setting->daftar_kategori ?? []); @endphp
                    @foreach($kategoris as $kat)
                        <a href="{{ route('home', ['kategori' => $kat]) }}" 
                           class="whitespace-nowrap px-6 md:px-8 py-3 md:py-4 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black transition-all uppercase tracking-widest {{ request('kategori') == $kat ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-50 dark:bg-slate-800/50 text-gray-400 dark:text-slate-500' }}">
                            {{ $kat }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        

        {{-- GRID KOLEKSI BUKU --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-8">
            @forelse($books as $book)
            <a href="{{ route('daftar_buku.show', $book->id) }}" 
               class="searchable-item group relative aspect-[1/1.5] overflow-hidden rounded-[1.8rem] md:rounded-[2.5rem] shadow-sm transition-all duration-500 hover:-translate-y-2 md:hover:-translate-y-3 hover:shadow-2xl bg-gray-100 dark:bg-slate-800 block">
                
                <img src="{{ asset('storage/' . $book->cover) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $book->judul }}">
                
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent opacity-90 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="absolute inset-0 p-4 md:p-6 flex flex-col justify-end">
                    <span class="mb-2 md:mb-3 w-fit px-2 md:px-3 py-1 md:py-1.5 bg-blue-600 text-white text-[6px] md:text-[8px] font-black rounded-md md:rounded-lg uppercase tracking-widest shadow-lg">
                        {{ $book->kategori ?? 'Umum' }}
                    </span>

                    <h4 class="text-white font-black text-xs md:text-lg leading-tight group-hover:text-blue-300 transition-colors duration-300 line-clamp-2 uppercase tracking-tighter">
                        {{ $book->judul }}
                    </h4>
                    <p class="text-gray-300 text-[8px] md:text-xs font-bold opacity-80 mt-1 md:mt-2 line-clamp-1 uppercase tracking-widest">
                        {{ $book->penulis }}
                    </p>

                    {{-- Menyembunyikan button --}}
                    <div class="hidden md:block max-h-0 overflow-hidden group-hover:max-h-16 group-hover:mt-5 transition-all duration-500 ease-in-out">
                        <div class="w-full py-3.5 bg-white text-gray-900 rounded-2xl font-black text-xs flex items-center justify-center shadow-2xl uppercase tracking-widest">
                            Detail
                        </div>
                    </div>
                </div>

                @if($book->is_recommended)
                <div class="absolute top-3 md:top-5 right-3 md:right-5 bg-amber-400 text-white p-1 md:p-2 rounded-lg md:rounded-xl shadow-xl">
                    <i class="ph-fill ph-star text-[8px] md:text-sm"></i>
                </div>
                @endif
            </a>
            @empty
            <div class="col-span-full py-20 md:py-32 text-center bg-gray-50 dark:bg-slate-900/50 rounded-[2.5rem] md:rounded-[4rem] border-2 md:border-4 border-dashed border-gray-100 dark:border-slate-800 transition-colors">
                <p class="text-gray-400 dark:text-slate-600 text-sm md:text-xl font-black uppercase tracking-widest italic">Buku tidak ditemukan.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection