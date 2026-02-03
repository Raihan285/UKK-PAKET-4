<nav class="bg-white border-b p-4 flex justify-between items-center">
    <span class="font-semibold text-gray-700">Selamat Datang, {{ Auth::user()->name }}</span>
    <div class="text-sm text-gray-500 uppercase tracking-widest">{{ Auth::user()->role }}</div>
</nav>