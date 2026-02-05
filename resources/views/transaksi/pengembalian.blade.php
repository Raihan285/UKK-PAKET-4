@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="border-b border-gray-100 pb-6">
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Kelola Pengembalian</h2>
        <p class="text-gray-400 text-sm font-medium">Daftar buku yang sedang dipinjam oleh siswa.</p>
    </div>

    <div class="bg-white rounded-4x1 border border-gray-100 overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50">
                <tr class="text-[10px] uppercase tracking-widest font-bold text-gray-400">
                    <th class="px-6 py-4">Cover</th>
                    <th class="px-6 py-4">Siswa</th>
                    <th class="px-6 py-4">Buku</th>
                    <th class="px-6 py-4">Batas Kembali</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-xs">
                @forelse($transactions as $trx)
                <tr class="hover:bg-gray-50/50 transition-all">
                    <td class="px-6 py-4">
                        <div class="w-10 h-14 rounded-lg overflow-hidden shadow-sm">
                            <img src="{{ asset('storage/' . $trx->book->cover) }}" class="w-full h-full object-cover">
                        </div>
                    </td>
                    <td class="px-6 py-4 font-bold text-blue-600">{{ $trx->user->name }}</td>
                    <td class="px-6 py-4 font-black text-gray-900">{{ $trx->book->judul }}</td>
                    <td class="px-6 py-4 text-red-500 font-bold">
                        {{ \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('transaksi.processReturn', $trx->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-emerald-500 text-white px-4 py-2 rounded-xl text-[10px] font-bold hover:bg-emerald-600 transition-all shadow-md">
                                Terima Kembali
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic font-medium">
                        Tidak ada buku yang sedang dipinjam saat ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection