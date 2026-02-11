@extends('layouts.guest')

@section('content')
{{-- Container utama menggunakan h-screen agar form tepat di tengah vertikal monitor --}}
<div class="min-h-screen bg-[#f3f4f6] flex items-center justify-center font-sans">
    
    {{-- Box Login: Ukuran dikunci pada 380px agar tidak 'melar' di desktop --}}
    <div class="w-[380px] bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
        
        {{-- Header dengan Logo --}}
        <div class="pt-10 pb-6 px-8 text-center">
            <img src="{{ asset('images/logobooktify.png') }}" alt="Logo" class="h-12 mx-auto mb-4 object-contain">
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Login Admin & Siswa</h1>
            <p class="text-xs text-gray-400 mt-1">Silahkan masuk ke sistem perpustakaan</p>
        </div>

        {{-- Area Form --}}
        <div class="px-8 pb-10">
            {{-- Notifikasi Error --}}
            @if($errors->any())
                <div class="mb-5 p-3 bg-red-50 border-l-2 border-red-500 rounded text-[11px] text-red-700 font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                
                {{-- Field Username --}}
                <div class="group">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 px-1">Username</label>
                    <input type="text" name="username" 
                           class="w-full px-3 py-2 bg-white border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all text-sm text-gray-700 placeholder:text-gray-300"
                           placeholder="Masukkan username anda" required>
                </div>

                {{-- Field Password --}}
                <div class="group">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 px-1">Password</label>
                    <input type="password" name="password" 
                           class="w-full px-3 py-2 bg-white border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all text-sm text-gray-700 placeholder:text-gray-300"
                           placeholder="••••••••" required>
                </div>

                {{-- Fitur Tambahan --}}
                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember" id="rem" class="w-3.5 h-3.5 border-gray-300 rounded text-blue-600 focus:ring-blue-500">
                        <label for="rem" class="text-[11px] text-gray-500 font-medium cursor-pointer">Ingat saya</label>
                    </div>
                    <a href="#" class="text-[11px] font-bold text-blue-600 hover:text-blue-700">Lupa password?</a>
                </div>

                {{-- Tombol Masuk --}}
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded shadow-md hover:shadow-lg transition-all text-[12px] uppercase tracking-widest mt-4">
                    Sign In
                </button>
            </form>

            {{-- Footer Card --}}
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-[11px] text-gray-500">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline ml-1">Daftar Sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection