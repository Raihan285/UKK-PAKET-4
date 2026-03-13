@extends('layouts.guest')

@section('content')
{{-- Kontainer Utama --}}
<div class="fixed inset-0 w-full h-full bg-slate-50 dark:bg-slate-950 flex items-center justify-center p-6 font-sans overflow-y-auto transition-colors duration-500 no-scrollbar">
    
    <div x-data="{ show: true }" 
         x-show="show" 
         x-cloak
         class="fixed inset-0 z-[200] flex items-center justify-center p-6 pointer-events-none">
        
        {{-- Alert Berhasil (Success) --}}
        @if(session('success'))
        <div x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="w-full max-w-sm bg-white dark:bg-slate-900 border-2 border-emerald-500 rounded-[2.5rem] p-8 shadow-[0_20px_60px_rgba(0,0,0,0.3)] text-center pointer-events-auto relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
            
            <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-900/30 rounded-3xl flex items-center justify-center text-emerald-500 mx-auto mb-6">
                <i class="ph-fill ph-check-circle text-5xl"></i>
            </div>
            
            <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-2">Berhasil!</h3>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 leading-relaxed">{{ session('success') }}</p>
            
            <button @click="show = false" class="mt-8 w-full py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black rounded-2xl text-[10px] uppercase tracking-[0.2em] hover:bg-slate-200 transition-colors">
                Tutup
            </button>
        </div>
        @endif

        {{-- Alert Gagal (Validation Errors) --}}
        @if($errors->any())
        <div x-show="show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             class="w-full max-w-sm bg-white dark:bg-slate-900 border-2 border-red-500 rounded-[2.5rem] p-8 shadow-[0_20px_60px_rgba(0,0,0,0.3)] pointer-events-auto relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-full h-2 bg-red-500"></div>
            
            <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 rounded-3xl flex items-center justify-center text-red-500 mx-auto mb-6">
                <i class="ph-fill ph-warning-circle text-5xl"></i>
            </div>
            
            <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-4 text-center">Ada Masalah!</h3>
            
            <ul class="space-y-2 mb-8">
                @foreach ($errors->all() as $error)
                    <li class="flex items-center gap-3 text-left">
                        <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                        <span class="text-[11px] font-bold text-slate-600 dark:text-slate-300 leading-tight">{{ $error }}</span>
                    </li>
                @endforeach
            </ul>
            
            <button @click="show = false" class="w-full py-4 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-black rounded-2xl text-[10px] uppercase tracking-[0.2em] hover:bg-red-100 transition-colors">
                Perbaiki Data
            </button>
        </div>
        @endif
    </div>

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
    <div class="w-full max-w-[580px] bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl dark:shadow-none border border-gray-100 dark:border-slate-800 p-8 md:p-10 relative z-10 transition-all duration-500 my-8">
        
        {{-- Header --}}
        <div class="flex flex-col items-center mb-8 text-center">
            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4 border border-gray-100 dark:border-slate-700">
                <img src="{{ asset('images/logobooktify.png') }}" alt="Logo" class="h-10 w-auto">
            </div>
            <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tighter uppercase">Join Booktify</h2>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Daftar Anggota Baru Perpustakaan</p>
        </div>

        {{-- Form Register --}}
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nama Lengkap --}}
                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Nama Lengkap</label>
                    <div class="relative">
                        <i class="ph-bold ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-indigo-500 dark:focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm text-slate-700 dark:text-white" placeholder="Nama Anda" required>
                    </div>
                </div>

                {{-- Username --}}
                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Username</label>
                    <div class="relative">
                        <i class="ph-bold ph-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                        <input type="text" name="username" value="{{ old('username') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-indigo-500 dark:focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm text-slate-700 dark:text-white" placeholder="User Baru" required>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Email --}}
                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Email</label>
                    <div class="relative">
                        <i class="ph-bold ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-indigo-500 dark:focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm text-slate-700 dark:text-white" placeholder="email@gmail.com" required>
                    </div>
                </div>

                {{-- Nomor Telepon --}}
                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Nomor Telepon</label>
                    <div class="relative">
                        <i class="ph-bold ph-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                        <input type="tel" name="telepon" value="{{ old('telepon') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-indigo-500 dark:focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm text-slate-700 dark:text-white" placeholder="08xxxxxxx" required>
                    </div>
                </div>
            </div>

            {{-- Alamat --}}
            <div class="group">
                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Alamat Lengkap</label>
                <div class="relative">
                    <i class="ph-bold ph-map-pin absolute left-4 top-4 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                    <textarea name="alamat" rows="2" class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-indigo-500 dark:focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm text-slate-700 dark:text-white resize-none" placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
                </div>
            </div>

            {{-- Password --}}
            <div class="group" x-data="{ show: false }">
                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Password</label>
                <div class="relative">
                    <i class="ph-bold ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                    <input :type="show ? 'text' : 'password'" name="password" class="w-full pl-11 pr-12 py-3 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-indigo-500 dark:focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm text-slate-700 dark:text-white" placeholder="••••••••" required>
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