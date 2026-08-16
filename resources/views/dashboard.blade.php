@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="font-display text-3xl font-bold tracking-tight text-ink">Panel de control</h1>
    <p class="mt-1 text-sm text-muted">Resumen general del bufete.</p>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('casos.index') }}" class="stat-card blue">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </span>
                <div>
                    <p class="stat-label">Casos en trámite</p>
                    <p class="stat-value text-primary-600">{{ $casosEnTramite }}</p>
                </div>
            </div>
        </a>
        <a href="{{ route('casos.index') }}" class="stat-card gray">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5A1.5 1.5 0 0 0 4.5 6H6v13.5A1.5 1.5 0 0 0 7.5 21h9a1.5 1.5 0 0 0 1.5-1.5V6h1.5A1.5 1.5 0 0 0 21 4.5V3m-10 5h2m-2 4h2m-2 4h2" />
                    </svg>
                </span>
                <div>
                    <p class="stat-label">Casos archivados</p>
                    <p class="stat-value text-gray-600">{{ $casosArchivados }}</p>
                </div>
            </div>
        </a>
        <a href="{{ route('clientes.index') }}" class="stat-card blue">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </span>
                <div>
                    <p class="stat-label">Clientes</p>
                    <p class="stat-value text-primary-600">{{ $totalClientes }}</p>
                </div>
            </div>
        </a>
        <a href="{{ route('abogados.index') }}" class="stat-card green">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-success-strong">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                </span>
                <div>
                    <p class="stat-label">Abogados</p>
                    <p class="stat-value text-success-strong">{{ $totalAbogados }}</p>
                </div>
            </div>
        </a>
    </div>

    <div class="mt-8 flex flex-wrap items-center gap-3">
        <a href="{{ route('casos.index') }}" class="btn-primary">Ver casos</a>
        <a href="{{ route('clientes.index') }}" class="btn-secondary">Ver clientes</a>
        <a href="{{ route('abogados.index') }}" class="btn-secondary">Ver abogados</a>
        <a href="{{ route('casos.export') }}" class="btn-ghost">Exportar Excel</a>
    </div>

    <div class="mt-10 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="card">
            <h2 class="card-title">Actividad reciente</h2>
            <p class="card-subtitle">Últimos clientes registrados.</p>
            <ul class="mt-4 divide-y divide-line/60">
                @forelse ($clientesRecientes as $cliente)
                    <li class="flex items-center justify-between gap-3 py-3">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-navy text-xs font-bold text-gold">
                                {{ strtoupper(substr($cliente->nombre, 0, 1)) }}{{ strtoupper(substr($cliente->apellido, 0, 1)) }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-ink">{{ $cliente->nombre_completo }}</p>
                                <p class="font-mono text-xs text-muted">{{ $cliente->cedula }}</p>
                            </div>
                        </div>
                        <a href="{{ route('clientes.show', $cliente) }}" class="link-action text-xs">Ver</a>
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-muted">Aún no hay clientes registrados.</li>
                @endforelse
            </ul>
        </div>

        <div class="card">
            <h2 class="card-title">Últimos abogados</h2>
            <p class="card-subtitle">Profesionales del bufete recientes.</p>
            <ul class="mt-4 divide-y divide-line/60">
                @forelse ($abogadosRecientes as $abogado)
                    <li class="flex items-center justify-between gap-3 py-3">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gold/20 text-xs font-bold text-navy">
                                {{ strtoupper(substr($abogado->nombre, 0, 1)) }}{{ strtoupper(substr($abogado->apellido, 0, 1)) }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-ink">{{ $abogado->nombre_completo }}</p>
                                <p class="text-xs font-medium" style="color: {{ $abogado->especialidad_color }}">{{ $abogado->especialidad }}</p>
                            </div>
                        </div>
                        <a href="{{ route('abogados.show', $abogado) }}" class="link-action text-xs">Ver</a>
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-muted">Aún no hay abogados registrados.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection