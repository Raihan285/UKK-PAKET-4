@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-slate-950 flex items-center justify-center p-6 font-sans transition-colors duration-500">
    
    {{-- Main Container: Meniru aspek rasio lebar pada gambar --}}
    <div class="w-full max-w-6xl bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-slate-800 flex overflow-hidden min-h-[600px] relative">
        
        {{-- Sisi Kiri: Form Area --}}
        <div class="w-full lg:w-1/2 p-12 md:p-20 flex flex-col justify-center relative z-10 bg-white dark:bg-slate-900">
            
            {{-- Language Selector (Optional, meniru gambar) --}}
            <div class="absolute top-10 left-12 md:left-20 flex items-center gap-2 cursor-pointer group">
                <img src="https://flagcdn.com/w20/id.png" class="w-5 h-3.5 object-cover rounded-sm" alt="ID">
                <span class="text-[11px] font-bold text-gray-400 group-hover:text-gray-600 transition-colors uppercase tracking-widest">Bahasa Indonesia</span>
                <i class="ph ph-caret-down text-[10px] text-gray-400"></i>
            </div>

            <div class="mb-10">
                <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Login Admin & Siswa</h2>
                <p class="text-gray-400 dark:text-slate-500 mt-2 font-medium">Silahkan masuk untuk mengakses layanan perpustakaan digital.</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-xl text-xs text-red-700 dark:text-red-400 font-bold uppercase tracking-tight">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-gray-900 dark:text-slate-300 uppercase tracking-widest">Username/NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="username" 
                           class="w-full px-5 py-4 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all text-sm text-gray-900 dark:text-white placeholder:text-gray-300 dark:placeholder:text-slate-600"
                           placeholder="Masukkan Username atau NIK" required>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="text-[11px] font-black text-gray-900 dark:text-slate-300 uppercase tracking-widest">Kata Sandi <span class="text-red-500">*</span></label>
                    </div>
                    <div class="relative group" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password" 
                               class="w-full px-5 py-4 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-600 outline-none transition-all text-sm text-gray-900 dark:text-white"
                               placeholder="••••••••" required>
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="ph-bold" :class="show ? 'ph-eye-slash' : 'ph-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="remember" class="peer h-4 w-4 cursor-pointer appearance-none rounded border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 checked:bg-blue-600 checked:border-blue-600 transition-all">
                            <i class="ph-bold ph-check absolute left-0.5 text-[10px] text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-slate-400 font-bold uppercase tracking-tighter">Ingat Saya</span>
                    </label>
                    <a href="#" class="text-xs font-black text-blue-600 dark:text-blue-400 hover:underline">Lupa Kata Sandi?</a>
                </div>

                <button type="submit" 
                        class="w-full bg-[#003366] dark:bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-lg transition-all active:scale-[0.98] uppercase text-xs tracking-[0.2em]">
                    Masuk
                </button>
            </form>

            <div class="mt-8 text-center md:text-left">
                <p class="text-xs text-gray-500 dark:text-slate-500 font-medium">
                    Belum memiliki akun? 
                    <a href="{{ route('register') }}" class="text-blue-700 dark:text-blue-400 font-black hover:underline ml-1">Daftar di sini</a>
                </p>
            </div>
        </div>

        {{-- Sisi Kanan: Geometric Illustration (Meniru gambar) --}}
        <div class="hidden lg:flex w-1/2 bg-white dark:bg-slate-800 items-center justify-center p-12 relative overflow-hidden">
            {{-- Pola Garis Geometris Biru (Custom SVG agar mirip gambar) --}}
            <svg class="w-4/5 h-4/5 text-blue-900 dark:text-blue-500 opacity-90" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M50 150L250 50L450 150V350L250 450L50 350V150Z" stroke="currentColor" stroke-width="8" stroke-linejoin="round"/>
                <path d="M250 50V450" stroke="currentColor" stroke-width="4" stroke-dasharray="10 10"/>
                <rect x="200" y="200" width="250" height="200" stroke="currentColor" stroke-width="8" stroke-linejoin="round"/>
                <path d="M50 150L450 350" stroke="currentColor" stroke-width="4"/>
            </svg>
            
            {{-- Overlay Logo Booktify di pojok kanan bawah agar tetap ada branding --}}
            <div class="absolute bottom-12 right-12">
                <img src="{{ asset('images/logobooktify.png') }}" alt="Logo" class="h-8 opacity-20 dark:opacity-40 grayscale">
            </div>
        </div>
    </div>
</div>
@endsection