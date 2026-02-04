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