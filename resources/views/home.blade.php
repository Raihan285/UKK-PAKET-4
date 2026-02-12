@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-10 py-6 transition-colors duration-500"> 
    
    {{-- 1. SECTION TITLE & DASHBOARD --}}
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

        <div class="space-y-6">
            <div class="border-l-4 border-blue-600 pl-4">
                <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight uppercase transition-colors">Dashboard Siswa</h2>
                <p class="text-gray-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em]">Ringkasan aktivitas kamu</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Card Buku Dipinjam --}}
                <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-sm flex items-center space-x-4 transition-all hover:shadow-md">
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl">
                        <i class="ph-bold ph-books text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Buku Dipinjam</p>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $peminjamanAktif->count() }}</h3>
                    </div>
                </div>

                {{-- Card Denda --}}
                <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-sm flex items-center space-x-4 transition-all hover:shadow-md">
                    <div class="p-4 {{ $totalDendaUser > 0 ? 'bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 animate-pulse' : 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' }} rounded-2xl">
                        <i class="ph-bold ph-hand-coins text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Tanggungan Denda</p>
                        <h3 class="text-2xl font-black {{ $totalDendaUser > 0 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                            Rp {{ number_format($totalDendaUser, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                {{-- Card Status --}}
                <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-sm flex items-center space-x-4 transition-all hover:shadow-md">
                    <div class="p-4 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-2xl">
                        <i class="ph-bold ph-identification-badge text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Status Anggota</p>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ Auth::user()->role }}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    {{-- 2. SECTION KOLEKSI & FILTER KATEGORI --}}
    <div class="space-y-8 pt-4">
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 border-b border-gray-100 dark:border-slate-800 pb-8 transition-colors">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Eksplorasi Buku</h2>
                <p class="text-gray-500 dark:text-slate-400 font-medium text-sm mt-1">
                    @if(request('kategori'))
                        Menampilkan kategori <span class="text-blue-600 dark:text-blue-400 font-bold uppercase">"{{ request('kategori') }}"</span>
                    @else
                        Temukan ribuan ilmu dalam satu genggaman.
                    @endif
                </p>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('home') }}" 
                   class="px-5 py-2.5 rounded-xl text-[10px] font-black transition-all uppercase tracking-tighter {{ !request('kategori') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 dark:shadow-none' : 'bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                   Semua Buku
                </a>

                @php $kategoris = array_filter($setting->daftar_kategori ?? []); @endphp
                @foreach($kategoris as $kat)
                    <a href="{{ route('home', ['kategori' => $kat]) }}" 
                       class="px-5 py-2.5 rounded-xl text-[10px] font-black transition-all uppercase tracking-tighter {{ request('kategori') == $kat ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 dark:shadow-none' : 'bg-gray-50 dark:bg-slate-800/50 text-gray-400 dark:text-slate-500 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                        {{ $kat }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- 3. GRID KOLEKSI BUKU --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse($books as $book)
            <a href="{{ route('daftar_buku.show', $book->id) }}" 
               class="searchable-item group relative aspect-[1/1.5] overflow-hidden rounded-[2rem] shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl bg-gray-100 dark:bg-slate-800 block">
                
                <img src="{{ asset('storage/' . $book->cover) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                {{-- Overlay Gradasi (Lebih gelap di mode malam) --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="absolute inset-0 p-5 flex flex-col justify-end">
                    <span class="mb-2 w-fit px-2.5 py-1 bg-blue-600 text-white text-[7px] font-black rounded-md uppercase tracking-widest shadow-sm">
                        {{ $book->kategori ?? 'Umum' }}
                    </span>

                    <h4 class="text-white font-black text-sm leading-tight group-hover:text-blue-300 transition-colors duration-300 line-clamp-2">
                        {{ $book->judul }}
                    </h4>
                    <p class="text-gray-300 text-[9px] font-bold opacity-80 mt-1 line-clamp-1 uppercase tracking-tighter">
                        {{ $book->penulis }}
                    </p>

                    <div class="max-h-0 overflow-hidden group-hover:max-h-12 group-hover:mt-4 transition-all duration-500 ease-in-out">
                        <div class="w-full py-2.5 bg-white text-gray-900 rounded-xl font-black text-[9px] flex items-center justify-center shadow-2xl uppercase tracking-tighter">
                            Lihat Detail
                        </div>
                    </div>
                </div>

                @if($book->is_recommended)
                <div class="absolute top-4 right-4 bg-amber-400 text-white p-1.5 rounded-lg shadow-lg">
                    <i class="ph-fill ph-star text-[10px]"></i>
                </div>
                @endif
            </a>
            @empty
            <div class="col-span-full py-20 text-center bg-gray-50 dark:bg-slate-900/50 rounded-[3rem] border-2 border-dashed border-gray-100 dark:border-slate-800 transition-colors">
                <p class="text-gray-400 dark:text-slate-600 text-sm font-black uppercase tracking-widest italic">Buku tidak ditemukan.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection