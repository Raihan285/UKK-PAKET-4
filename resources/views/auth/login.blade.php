@extends('layouts.guest')

@section('content')
<div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-blue-600">Booktify</h1>
        <p class="text-gray-500">Masuk untuk melanjutkan</p>
    </div>

    @if($errors->any())
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none" required>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
            Login
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
        Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-bold">Daftar Anggota</a>
    </p>
</div>
@endsection