@extends('layouts.guest')

@section('content')
{{-- Kontainer Utama --}}
<div class="fixed inset-0 w-full h-full bg-slate-50 dark:bg-slate-950 flex items-center justify-center p-6 font-sans overflow-hidden transition-colors duration-500">
    
    {{-- Tombol Dark Mode --}}
    <div class="absolute top-8 right-8 z-50">
        <button 
            onclick="toggleDarkMode()" 
            type="button"
            class="w-12 h-12 flex items-center justify-center bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl shadow-sm hover:shadow-md hover:scale-110 active:scale-95 transition-all cursor-pointer group"
        >
            <i class="ph-bold ph-moon text-indigo-600 dark:hidden text-xl group-hover:rotate-12 transition-transform"></i>
            <i class="ph-bold ph-sun text-yellow-400 hidden dark:block text-xl group-hover:rotate-90 transition-transform"></i>
        </button>
    </div>

    {{-- Dekorasi Latar Belakang --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-100 dark:bg-indigo-900/10 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-100 dark:bg-blue-900/10 rounded-full blur-[100px]"></div>
    </div>

    {{-- Card Login --}}
    <div class="w-full max-w-[440px] bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 dark:shadow-none border border-gray-100 dark:border-slate-800 p-10 md:p-12 relative z-10 transition-all duration-500">
        
        <div class="flex flex-col items-center mb-10">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-[2rem] flex items-center justify-center mb-6 shadow-inner border border-gray-100 dark:border-slate-700 transition-all duration-500">
                <img src="{{ asset('images/logobooktify.png') }}" alt="Logo" class="h-12 w-auto drop-shadow-sm">
            </div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase">Booktify</h2>
            <div class="h-1 w-8 bg-indigo-600 rounded-full mt-2"></div>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-r-xl">
                <p class="text-[11px] text-red-600 dark:text-red-400 font-black uppercase tracking-widest leading-tight">
                    {{ $errors->first() }}
                </p>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            <div class="group">
                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Username</label>
                <div class="relative">
                    <i class="ph-bold ph-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                    <input type="text" name="username" class="w-full pl-11 pr-4 py-4 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-indigo-500 dark:focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm text-slate-700 dark:text-white placeholder:text-slate-300" placeholder="Username" required>
                </div>
            </div>

            <div class="group">
                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Password</label>
                <div class="relative" x-data="{ show: false }">
                    <i class="ph-bold ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                    <input :type="show ? 'text' : 'password'" name="password" class="w-full pl-11 pr-12 py-4 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-indigo-500 dark:focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm text-slate-700 dark:text-white placeholder:text-slate-300" placeholder="••••••••" required>
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-indigo-600 transition-colors">
                        <i class="ph-bold" :class="show ? 'ph-eye-slash' : 'ph-eye'"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-200 dark:shadow-none transition-all active:scale-[0.98] text-xs uppercase tracking-[0.2em] mt-2">
                Sign In
            </button>
        </form>

        <div class="mt-10 pt-8 border-t border-slate-50 dark:border-slate-800 text-center">
            <p class="text-xs text-slate-400 font-medium">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 font-black hover:underline ml-1 uppercase tracking-widest text-[10px]">Daftar</a>
            </p>
        </div>
    </div>
</div>

{{-- Script Tambahan Dark Mode --}}
<script>
    function toggleDarkMode() {
        const html = document.documentElement;
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }

    // Logika Agar Tema Sesuai Dengan Halaman Yang Dibuat
    (function() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    })();
</script>

<script src="https://unpkg.com/@phosphor-icons/web"></script>
@endsection