<aside class="w-64 bg-white border-r flex flex-col pt-0 pb-6">
    
    <div class="px-4 -mt-4 mb-0">
        <img src="{{ asset('images/logobooktify.png') }}" 
             alt="Booktify" 
             class="w-full h-auto block object-contain">
    </div>
    
    <nav class="flex-1 px-4 -mt-8">
        <a href="{{ route('home') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600' : 'text-gray-400' }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-house text-lg"></i> 
            <span>Beranda</span>
        </a>
        
        <a href="#" class="flex items-center space-x-3 px-4 py-2.5 text-gray-400 hover:bg-gray-50 rounded-xl transition-all">
            <i class="ph ph-book-open text-lg"></i> 
            <span>Daftar Buku</span>
        </a>

        <a href="#" class="flex items-center space-x-3 px-4 py-2.5 text-gray-400 hover:bg-gray-50 rounded-xl transition-all">
            <i class="ph ph-hand-pointing text-lg"></i> 
            <span>Peminjaman</span>
        </a>
        
        @if(Auth::user()->role == 'admin')
        <div class="mt-4 mb-1 px-4 text-[10px] font-bold text-gray-300 uppercase tracking-widest">Administrator</div>
        
        <a href="{{ route('buku.index') }}" class="flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('buku.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:bg-gray-50' }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-notebook text-lg"></i> 
            <span>Kelola Buku</span>
        </a>

        <a href="{{ route('anggota.index') }}" class="flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('anggota.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-400 hover:bg-gray-50' }} rounded-xl font-semibold transition-all">
            <i class="ph-bold ph-users text-lg"></i> 
            <span>Kelola Anggota</span>
        </a>
        @endif
    </nav>
    
</aside>