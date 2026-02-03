@extends('layouts.guest')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Daftar Anggota Baru</h2>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label>Nama Lengkap</label>
            <input type="text" name="name" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-4">
            <label>Username</label>
            <input type="text" name="username" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-4">
            <label>Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-6">
            <label>Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded" required>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Daftar Sekarang</button>
    </form>
</div>
@endsection