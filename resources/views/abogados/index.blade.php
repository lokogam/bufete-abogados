@extends('layouts.app')

@section('title', 'Abogados')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-display text-3xl font-bold tracking-tight text-ink">Abogados</h1>
            <p class="mt-1 text-sm text-muted">{{ $abogados->total() }} abogados registrados.</p>
        </div>
        <a href="{{ route('abogados.create') }}" class="btn-primary">
            Nuevo abogado
        </a>
    </div>

    <form method="GET" action="{{ route('abogados.index') }}" class="mt-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
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
                placeholder="Buscar por cédula, nombre, especialidad o email..."
                class="search-input"
            >
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary btn-sm">Buscar</button>
            @if (request('q'))
                <a href="{{ route('abogados.index') }}" class="btn-secondary btn-sm">Limpiar</a>
            @endif
        </div>
    </form>

    <div class="data-table mt-6 desktop-only">
        <table>
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Especialidad</th>
                    <th>Email</th>
                    <th>Casos</th>
                    <th class="cell-actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($abogados as $abogado)
                    <tr>
                        <td class="font-mono text-xs">{{ $abogado->cedula }}</td>
                        <td class="font-medium">{{ $abogado->nombre_completo }}</td>
                        <td>
                            <span class="text-sm font-medium" style="color: {{ $abogado->especialidad_color }}">{{ $abogado->especialidad ?? '—' }}</span>
                        </td>
                        <td>{{ $abogado->email ?? '—' }}</td>
                        <td><span class="badge-gray">{{ $abogado->casos_count }} casos</span></td>
                        <td class="cell-actions">
                            <a href="{{ route('abogados.show', $abogado) }}" class="link-action">Ver</a>
                            <a href="{{ route('abogados.edit', $abogado) }}" class="link-edit ml-3">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-muted">No hay abogados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 space-y-3 mobile-only">
        @forelse ($abogados as $abogado)
            <div class="card p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-ink">{{ $abogado->nombre_completo }}</p>
                        <p class="mt-0.5 font-mono text-xs text-muted">{{ $abogado->cedula }}</p>
                    </div>
                    <span class="badge-gray">{{ $abogado->casos_count }} casos</span>
                </div>
                <dl class="mt-3 space-y-1.5 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-muted">Especialidad</dt>
                        <dd class="font-medium" style="color: {{ $abogado->especialidad_color }}">{{ $abogado->especialidad ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-muted">Email</dt>
                        <dd class="font-medium">{{ $abogado->email ?? '—' }}</dd>
                    </div>
                </dl>
                <div class="mt-4 flex gap-4 border-t border-line/60 pt-3">
                    <a href="{{ route('abogados.show', $abogado) }}" class="link-action text-sm">Ver detalle</a>
                    <a href="{{ route('abogados.edit', $abogado) }}" class="link-edit text-sm">Editar</a>
                </div>
            </div>
        @empty
            <div class="empty-state card">
                <p>No hay abogados registrados.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $abogados->links() }}
    </div>
@endsection