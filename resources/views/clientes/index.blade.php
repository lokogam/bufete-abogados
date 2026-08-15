@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Clientes</h1>
            <p class="mt-1 text-sm text-gray-500">{{ count($clientes) }} clientes registrados.</p>
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
@endsection