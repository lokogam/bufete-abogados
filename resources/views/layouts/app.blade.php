<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Bufete de Abogados') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg text-ink antialiased">
    <header class="app-header">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" class="nav-brand">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gold text-navy">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v.364c.396.22.78.48 1.146.766L12 6.045 10.104 4.13c.366-.286.75-.545 1.146-.766V3a.75.75 0 0 1 .75-.75ZM4.288 6.867a.75.75 0 0 1 1.06-.053L12 12.42l6.652-5.606a.75.75 0 1 1 1.008 1.11l-7.06 5.95V21.75a.75.75 0 0 1-1.5 0v-7.876l-7.06-5.95a.75.75 0 0 1-.752-1.047Z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="font-display text-lg font-bold tracking-tight text-white">Bufete de Abogados</span>
            </a>

            <nav class="hidden items-center gap-5 md:flex">
                @auth
                    <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">Clientes</a>
                    <a href="{{ route('abogados.index') }}" class="nav-link {{ request()->routeIs('abogados.*') ? 'active' : '' }}">Abogados</a>
                    <a href="{{ route('casos.index') }}" class="nav-link {{ request()->routeIs('casos.*') ? 'active' : '' }}">Casos</a>
                    <a href="{{ route('casos.export') }}" class="btn-gold btn-sm">Exportar Excel</a>
                    <div class="ml-2 flex items-center gap-3 border-l border-white/10 pl-4">
                        <span class="text-sm font-medium text-slate-200">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-ghost btn-sm text-slate-300 hover:text-white">
                                Salir
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn-gold btn-sm">Registrarse</a>
                @endauth
            </nav>

            <button type="button" id="menu-toggle" class="btn-ghost btn-sm text-white md:hidden" aria-expanded="false" aria-controls="mobile-menu">
                <span class="sr-only">Abrir menú</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        <nav id="mobile-menu" class="hidden border-t border-white/10 px-4 pb-4 pt-2 md:hidden">
            @auth
                <div class="flex flex-col gap-1">
                    <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">Clientes</a>
                    <a href="{{ route('abogados.index') }}" class="nav-link {{ request()->routeIs('abogados.*') ? 'active' : '' }}">Abogados</a>
                    <a href="{{ route('casos.index') }}" class="nav-link {{ request()->routeIs('casos.*') ? 'active' : '' }}">Casos</a>
                    <a href="{{ route('casos.export') }}" class="btn-gold btn-sm mt-2 w-full">Exportar Excel</a>
                    <div class="mt-2 flex items-center justify-between border-t border-white/10 pt-3">
                        <span class="text-sm font-medium text-slate-200">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-ghost btn-sm text-slate-300 hover:text-white">
                                Salir
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="flex flex-col gap-1">
                    <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn-gold btn-sm mt-2 w-full">Registrarse</a>
                </div>
            @endauth
        </nav>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-600">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="app-footer mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        Prueba técnica · Laravel 13 · MySQL · Docker
    </footer>

    <script>
        document.getElementById('menu-toggle')?.addEventListener('click', function () {
            const menu = document.getElementById('mobile-menu');
            const open = menu.classList.toggle('hidden');
            this.setAttribute('aria-expanded', String(!open));
        });
    </script>
</body>
</html>