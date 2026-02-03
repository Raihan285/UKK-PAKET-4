@extends('layouts.app')

@section('content')
<div class="bg-red-50 border border-red-100 text-red-400 px-4 py-2 rounded-lg flex items-center mb-8 text-[11px] animate-pulse">
    <i class="ph-bold ph-warning-circle mr-2"></i> Aplikasi kami masih dalam tahap pengembangan
</div>

<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Rekomendasi Buku</h2>
        <p class="text-gray-400 text-sm">Temukan inspirasi baca kamu!</p>
    </div>
    <div class="flex gap-2">
        <button class="px-5 py-2 bg-orange-50 text-orange-500 border border-orange-100 rounded-full text-[11px] font-bold">Direkomendasikan</button>
        <button class="px-5 py-2 bg-gray-100 text-gray-400 rounded-full text-[11px] font-bold hover:bg-gray-200 transition-colors">Agama</button>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
    @foreach($books as $book)
    <div class="group relative rounded-[2.5rem] overflow-hidden aspect-[3/4.2] shadow-sm hover:shadow-2xl transition-all duration-500">
        <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/10 to-transparent p-7 flex flex-col justify-end">
            <span class="bg-blue-600/40 backdrop-blur-md text-blue-200 text-[9px] font-bold px-3 py-1 rounded-full w-fit mb-3 uppercase tracking-widest border border-white/10">
                {{ $book->category }}
            </span>
            <h3 class="text-white font-bold text-base leading-tight">{{ $book->title }}</h3>
            <p class="text-gray-400 text-[11px] mt-1">{{ $book->author }}</p>
        </div>
    </div>
    @endforeach
</div>
@endsection