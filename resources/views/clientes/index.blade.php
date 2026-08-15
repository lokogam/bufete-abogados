@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Clientes</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $clientes->total() }} clientes registrados.</p>
        </div>
        <a href="{{ route('clientes.create') }}" class="btn-primary">
            Nuevo cliente
        </a>
    </div>

    @if (session('success'))
        <div class="mt-6 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-600">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('clientes.index') }}" class="mt-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
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
                placeholder="Buscar por cédula, nombre, email o teléfono..."
                class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
            >
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary btn-sm">Buscar</button>
            @if (request('q'))
                <a href="{{ route('clientes.index') }}" class="btn-secondary btn-sm">Limpiar</a>
            @endif
        </div>
    </form>

    <div class="data-table mt-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Casos</th>
                    <th class="cell-actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->cedula }}</td>
                        <td class="font-medium">{{ $cliente->nombre_completo }}</td>
                        <td>{{ $cliente->email ?? '—' }}</td>
                        <td>{{ $cliente->telefono ?? '—' }}</td>
                        <td>
                            <span class="badge-gray">{{ $cliente->casos_count }} casos</span>
                        </td>
                        <td class="cell-actions">
                            <a href="{{ route('clientes.show', $cliente) }}" class="link-action">Ver</a>
                            <a href="{{ route('clientes.edit', $cliente) }}" class="link-action ml-3">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-gray-400">No hay clientes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $clientes->links() }}
    </div>
@endsection