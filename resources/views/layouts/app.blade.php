<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booktify - Digital Library</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/phosphor-icons@2.1.0"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('globalSearch');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                // Mencari Baris Tabel (untuk halaman manajemen) 
                // DAN Card dengan class 'searchable-item' (untuk halaman beranda)
                const targets = document.querySelectorAll('tbody tr, .searchable-item');

                targets.forEach(el => {
                    const text = el.textContent.toLowerCase();
                    
                    if (text.includes(searchTerm)) {
                        // Jika ketemu, tampilkan sesuai jenis elemennya
                        if (el.tagName === 'TR') {
                            el.style.display = 'table-row';
                        } else {
                            el.style.display = 'block'; 
                        }
                        el.style.opacity = '1';
                    } else {
                        // Jika tidak ketemu, sembunyikan
                        el.style.display = 'none';
                    }
                });

                // Opsional: Berikan feedback jika hasil kosong
                const visibleItems = Array.from(targets).filter(t => t.style.display !== 'none');
                console.log('Menampilkan ' + visibleItems.length + ' hasil untuk: ' + searchTerm);
            });
        }
    });
    </script>
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        @include('layouts.sidebar')

        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>

        @include('layouts.right-panel')
        <style>[x-cloak] { display: none !important; }</style>
    </div>
</body>
</html>