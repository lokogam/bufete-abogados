<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Bufete de Abogados') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
    <header class="bg-white shadow-sm">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <span class="text-2xl">⚖️</span>
                <span class="text-lg font-semibold">Bufete de Abogados</span>
            </a>
            <nav class="flex items-center gap-4 text-sm font-medium">
                <a href="{{ route('casos.index') }}" class="text-gray-600 hover:text-gray-900">Casos</a>
                <a href="{{ route('casos.export') }}" class="rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-700">
                    Exportar Excel
                </a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <footer class="mx-auto max-w-7xl px-4 pb-8 text-center text-xs text-gray-400 sm:px-6 lg:px-8">
        Prueba técnica · Laravel 13 · MySQL · Docker
    </footer>
</body>
</html>