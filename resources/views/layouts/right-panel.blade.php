<aside class="w-80 bg-white border-l p-8 flex flex-col">
    <div class="flex items-center justify-end space-x-3 mb-8">
        <div class="text-right">
            <p class="text-xs font-bold text-gray-900">{{ Auth::user()->name }}</p>
            <p class="text-[10px] text-gray-400 italic capitalize">{{ Auth::user()->role }}</p>
        </div>
        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=fdf2f2&color=f97316" class="w-10 h-10 rounded-full border-2 border-orange-100">
    </div>

    <div class="relative mb-10">
        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" placeholder="Cari buku..." class="w-full bg-gray-50 border-none rounded-2xl py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-blue-100 transition-all">
    </div>

    <div class="mt-auto space-y-6">
        <div class="p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
            <p class="text-[10px] text-gray-400 mb-3 font-semibold uppercase tracking-wider">Ganti Bahasa</p>
            <div class="flex gap-2">
                <button class="flex-1 bg-white border border-orange-200 text-[10px] py-2 rounded-lg font-bold flex items-center justify-center gap-2 shadow-sm">
                    <img src="https://flagcdn.com/id.svg" class="w-4"> ID
                </button>
                <button class="flex-1 text-gray-400 text-[10px] py-2 rounded-lg font-bold flex items-center justify-center gap-2">
                    <img src="https://flagcdn.com/gb.svg" class="w-4 opacity-40"> EN
                </button>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center space-x-2 p-3 text-red-500 hover:bg-red-50 rounded-xl transition-all font-semibold text-sm">
                <i class="ph ph-sign-out"></i> <span>Keluar Aplikasi</span>
            </button>
        </form>
    </div>
</aside>