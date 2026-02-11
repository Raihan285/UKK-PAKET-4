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
            {{-- Ganti bagian <tbody> Admin dengan kode ini --}}
            <tbody class="divide-y divide-gray-50">
                @forelse($transactions as $trx)
                <tr class="group hover:bg-blue-50/30 transition-all duration-300">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-5">
                            <div class="w-12 h-16 bg-gray-100 rounded-xl overflow-hidden shadow-sm shrink-0 group-hover:scale-105 transition-transform duration-500">
                                <img src="{{ asset('storage/' . $trx->book->cover) }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-black text-gray-900 text-xs uppercase tracking-tight">{{ $trx->book->judul }}</p>
                                <p class="text-[9px] text-blue-500 font-bold uppercase tracking-widest mt-1">{{ $trx->book->kategori }}</p>
                            </div>
                        </div>
                    </td>
                    
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-[10px] font-bold text-white uppercase shadow-sm">
                                {{ substr($trx->user->name, 0, 2) }}
                            </div>
                            <span class="text-xs font-bold text-gray-700">{{ $trx->user->name }}</span>
                        </div>
                    </td>

                    <td class="px-8 py-5 text-center">
                        <span class="px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-tighter inline-flex items-center gap-2
                            {{ $trx->status == 'menunggu' ? 'bg-amber-100 text-amber-600' : ($trx->status == 'dipinjam' ? 'bg-blue-100 text-blue-600' : 'bg-emerald-100 text-emerald-600') }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                            {{ $trx->status }}
                        </span>
                    </td>

                    <td class="px-8 py-5">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black {{ \Carbon\Carbon::parse($trx->tanggal_kembali)->isPast() && $trx->status == 'dipinjam' ? 'text-red-500' : 'text-gray-900' }}">
                                {{ $trx->tanggal_kembali ? \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d M, Y') : '---' }}
                            </span>
                            @if(\Carbon\Carbon::parse($trx->tanggal_kembali)->isPast() && $trx->status == 'dipinjam')
                                <span class="text-[7px] text-red-400 font-black uppercase tracking-widest">Terlambat!</span>
                            @endif
                        </div>
                    </td>

                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end items-center gap-2">
                            @if($trx->status == 'menunggu')
                                <form action="{{ route('transaksi.approve', $trx->id) }}" method="POST">
                                    @csrf
                                    <button class="group/btn bg-gray-900 text-white px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all flex items-center gap-2">
                                        <i class="ph-bold ph-check text-xs group-hover/btn:scale-110 transition-transform"></i>
                                        Setujui
                                    </button>
                                </form>
                            @elseif($trx->status == 'dipinjam')
                                <form action="{{ route('transaksi.return', $trx->id) }}" method="POST">
                                    @csrf
                                    <button class="group/btn bg-white border border-gray-200 text-gray-900 px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:border-emerald-500 hover:text-emerald-600 transition-all flex items-center gap-2 shadow-sm">
                                        <i class="ph-bold ph-arrow-u-up-left text-xs group-hover/btn:-translate-y-0.5 transition-transform"></i>
                                        Kembalikan
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center gap-2 text-emerald-500 bg-emerald-50 px-4 py-2 rounded-xl">
                                    <i class="ph-bold ph-seal-check text-sm"></i>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Selesai</span>
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Kode @empty tetap sama seperti sebelumnya --}}
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection