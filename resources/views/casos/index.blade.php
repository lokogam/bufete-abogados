@extends('layouts.app')

@section('title', 'Casos')

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Casos</h1>
            <p class="mt-1 text-sm text-gray-500">Expedientes registrados en orden ascendente.</p>
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

    @if (session('success'))
        <div class="mt-6 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-600">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('casos.index') }}" class="mt-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
        <div class="relative flex-1">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                </svg>
            </span>
            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="Buscar por expediente, cliente o estado..."
                class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
            >
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary btn-sm">Buscar</button>
            @if (request('q'))
                <a href="{{ route('casos.index') }}" class="btn-secondary btn-sm">Limpiar</a>
            @endif
        </div>
    </form>

    <div class="data-table mt-6">
        <table class="min-w-full divide-y divide-gray-200">
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
                        <td class="font-medium">{{ $caso->numero_expediente }}</td>
                        <td>{{ $caso->cliente->nombre_completo }}</td>
                        <td>
                            <span class="badge-blue">{{ $caso->estado->label() }}</span>
                        </td>
                        <td>{{ $caso->fecha_inicio?->format('d/m/Y') }}</td>
                        <td>{{ $caso->fecha_finalizacion?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ $caso->abogados->pluck('nombre_completo')->implode(', ') ?: '—' }}</td>
                        <td class="cell-actions">
                            <a href="{{ route('casos.show', $caso) }}" class="link-action">Ver</a>
                            <a href="{{ route('casos.edit', $caso) }}" class="link-action ml-3">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-gray-400">No hay casos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $casos->links() }}
    </div>
@endsection