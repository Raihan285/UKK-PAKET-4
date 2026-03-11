@extends('layouts.guest')

@section('content')
{{-- Kontainer Utama --}}
<div class="fixed inset-0 w-full h-full bg-slate-50 dark:bg-slate-950 flex items-center justify-center p-6 font-sans overflow-y-auto transition-colors duration-500">
    
    {{-- Tombol Dark Mode --}}
    <div class="absolute top-8 right-8 z-50">
        <button 
            onclick="toggleDarkMode()" 
            type="button"
            class="w-12 h-12 flex items-center justify-center bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl shadow-sm hover:scale-110 active:scale-95 transition-all cursor-pointer group"
        >
            <i class="ph-bold ph-moon text-indigo-600 dark:hidden text-xl group-hover:rotate-12 transition-transform"></i>
            <i class="ph-bold ph-sun text-yellow-400 hidden dark:block text-xl group-hover:rotate-90 transition-transform"></i>
        </button>
    </div>

    {{-- Dekorasi Latar Belakang --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-100 dark:bg-indigo-900/10 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-100 dark:bg-blue-900/10 rounded-full blur-[100px]"></div>
    </div>

    {{-- Card Register --}}
    <div class="w-full max-w-[480px] bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl dark:shadow-none border border-gray-100 dark:border-slate-800 p-10 md:p-12 relative z-10 transition-all duration-500 my-8">
        
        {{-- Header --}}
        <div class="flex flex-col items-center mb-8 text-center">
            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4 border border-gray-100 dark:border-slate-700">
                <img src="{{ asset('images/logobooktify.png') }}" alt="Logo" class="h-10 w-auto">
            </div>
            <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tighter uppercase">Join Booktify</h2>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Daftar Anggota Baru Perpustakaan</p>
        </div>

        {{-- Form Register --}}
        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf
            
            {{-- Nama Lengkap --}}
            <div class="group">
                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Nama Lengkap</label>
                <div class="relative">
                    <i class="ph-bold ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                    <input type="text" name="name" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-indigo-500 dark:focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm text-slate-700 dark:text-white" placeholder="Nama Anda" required>
                </div>
            </div>

            {{-- Username --}}
            <div class="group">
                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Username</label>
                <div class="relative">
                    <i class="ph-bold ph-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                    <input type="text" name="username" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-indigo-500 dark:focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm text-slate-700 dark:text-white" placeholder="Username Baru" required>
                </div>
            </div>

            {{-- Email --}}
            <div class="group">
                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Email</label>
                <div class="relative">
                    <i class="ph-bold ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                    <input type="email" name="email" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-indigo-500 dark:focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm text-slate-700 dark:text-white" placeholder="email@gmail.com" required>
                </div>
            </div>

            {{-- Password --}}
            <div class="group" x-data="{ show: false }">
                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Password</label>
                <div class="relative">
                    <i class="ph-bold ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                    <input :type="show ? 'text' : 'password'" name="password" class="w-full pl-11 pr-12 py-3.5 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-indigo-500 dark:focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm text-slate-700 dark:text-white" placeholder="••••••••" required>
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-indigo-600 transition-colors">
                        <i class="ph-bold" :class="show ? 'ph-eye-slash' : 'ph-eye'"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-200 dark:shadow-none transition-all active:scale-[0.98] text-xs uppercase tracking-[0.2em] mt-4">
                Daftar Sekarang
            </button>
        </form>

        {{-- Footer --}}
        <div class="mt-8 pt-6 border-t border-slate-50 dark:border-slate-800 text-center">
            <p class="text-xs text-slate-400 font-medium">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 font-black hover:underline ml-1 uppercase tracking-widest text-[10px]">Sign In</a>
            </p>
        </div>
    </div>
</div>
@endsection