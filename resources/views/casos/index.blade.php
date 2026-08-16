@extends('layouts.app')

@section('title', 'Casos')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-display text-3xl font-bold tracking-tight text-ink">Casos</h1>
            <p class="mt-1 text-sm text-muted">Expedientes registrados en orden ascendente.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('casos.export') }}" class="btn-secondary btn-sm">
                Exportar Excel
            </a>
            <a href="{{ route('casos.create') }}" class="btn-primary btn-sm">
                Nuevo caso
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('casos.index') }}" class="mt-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
        @if (request('estado'))
            <input type="hidden" name="estado" value="{{ request('estado') }}">
        @endif
        <div class="search-box">
            <span class="search-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                </svg>
            </span>
            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="Buscar por expediente, cliente o estado..."
                class="search-input"
            >
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary btn-sm">Buscar</button>
            @if (request()->has('q') || request()->has('estado'))
                <a href="{{ route('casos.index') }}" class="btn-secondary btn-sm">Limpiar</a>
            @endif
        </div>
    </form>

    <div class="filter-chips mt-4">
        <a href="{{ route('casos.index', array_filter(['q' => request('q')])) }}" class="filter-chip {{ blank(request('estado')) ? 'active' : '' }}">
            Todos
        </a>
        @foreach ($estados as $estado)
            <a href="{{ route('casos.index', array_filter(['q' => request('q'), 'estado' => $estado->value])) }}"
               class="filter-chip {{ request('estado') === $estado->value ? 'active' : '' }}">
                {{ $estado->label() }}
            </a>
        @endforeach
    </div>

    <div class="data-table mt-4 desktop-only">
        <table>
            <thead>
                <tr>
                    <th>Expediente</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Inicio</th>
                    <th>Finalización</th>
                    <th>Abogados</th>
                    <th class="cell-actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($casos as $caso)
                    <tr>
                        <td class="font-mono text-xs font-medium">{{ $caso->numero_expediente }}</td>
                        <td class="font-medium">{{ $caso->cliente->nombre_completo }}</td>
                        <td>
                            <span class="badge {{ $caso->estado->badgeClass() }}">{{ $caso->estado->label() }}</span>
                        </td>
                        <td class="font-mono text-xs">{{ $caso->fecha_inicio?->format('d/m/Y') }}</td>
                        <td class="font-mono text-xs">{{ $caso->fecha_finalizacion?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ $caso->abogados->pluck('nombre_completo')->implode(', ') ?: '—' }}</td>
                        <td class="cell-actions">
                            <a href="{{ route('casos.show', $caso) }}" class="link-action">Ver</a>
                            <a href="{{ route('casos.edit', $caso) }}" class="link-edit ml-3">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-muted">No hay casos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 space-y-3 mobile-only">
        @forelse ($casos as $caso)
            <div class="card p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-mono text-sm font-semibold text-ink">{{ $caso->numero_expediente }}</p>
                        <p class="mt-0.5 text-sm font-medium">{{ $caso->cliente->nombre_completo }}</p>
                    </div>
                    <span class="badge {{ $caso->estado->badgeClass() }}">{{ $caso->estado->label() }}</span>
                </div>
                <dl class="mt-3 space-y-1.5 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-muted">Inicio</dt>
                        <dd class="font-mono text-xs font-medium">{{ $caso->fecha_inicio?->format('d/m/Y') }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-muted">Finalización</dt>
                        <dd class="font-mono text-xs font-medium">{{ $caso->fecha_finalizacion?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-muted">Abogados</dt>
                        <dd class="max-w-[55%] truncate text-right font-medium">{{ $caso->abogados->pluck('nombre_completo')->implode(', ') ?: '—' }}</dd>
                    </div>
                </dl>
                <div class="mt-4 flex gap-4 border-t border-line/60 pt-3">
                    <a href="{{ route('casos.show', $caso) }}" class="link-action text-sm">Ver detalle</a>
                    <a href="{{ route('casos.edit', $caso) }}" class="link-edit text-sm">Editar</a>
                </div>
            </div>
        @empty
            <div class="empty-state card">
                <p>No hay casos registrados.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $casos->links() }}
    </div>
@endsection