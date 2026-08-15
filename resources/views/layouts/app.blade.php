<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Bufete de Abogados') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
    <header class="app-header">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <span class="text-2xl">⚖️</span>
                <span class="text-lg font-semibold">Bufete de Abogados</span>
            </a>
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ route('clientes.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Clientes</a>
                    <a href="{{ route('abogados.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Abogados</a>
                    <a href="{{ route('casos.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Casos</a>
                    <a href="{{ route('casos.export') }}" class="btn-primary btn-sm">
                        Exportar Excel
                    </a>
                    <div class="ml-2 flex items-center gap-3 border-l border-gray-200 pl-4">
                        <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-ghost btn-sm">
                                Salir
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn-primary btn-sm">
                        Registrarse
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <footer class="app-footer mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        Prueba técnica · Laravel 13 · MySQL · Docker
    </footer>
</body>
</html>