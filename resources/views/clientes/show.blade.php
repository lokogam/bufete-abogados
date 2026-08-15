@extends('layouts.app')

@section('title', "Cliente · {$cliente->nombre_completo}")

@section('content')
    <a href="{{ route('clientes.index') }}" class="back-link">← Volver a clientes</a>

    <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ $cliente->nombre_completo }}</h1>
            <p class="mt-1 text-sm text-gray-500">Cédula {{ $cliente->cedula }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('clientes.edit', $cliente) }}" class="btn-secondary">Editar</a>
            <form method="POST" action="{{ route('clientes.destroy', $cliente) }}"
                onsubmit="return confirm('¿Eliminar este cliente? Esta acción es reversible.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn bg-danger-600 text-white hover:bg-danger-700">Eliminar</button>
            </form>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="card">
            <h2 class="card-title">Información personal</h2>
            <dl class="detail-list mt-4">
                <div class="detail-item">
                    <dt>Nombre completo</dt>
                    <dd>{{ $cliente->nombre_completo }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Cédula</dt>
                    <dd>{{ $cliente->cedula }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Email</dt>
                    <dd>{{ $cliente->email ?? '—' }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Teléfono</dt>
                    <dd>{{ $cliente->telefono ?? '—' }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Dirección</dt>
                    <dd>{{ $cliente->direccion ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Casos del cliente ({{ $cliente->casos->count() }})</h2>
                <a href="{{ route('casos.create') }}" class="link-action">Nuevo caso</a>
            </div>
            @forelse ($cliente->casos as $caso)
                <div class="flex items-center justify-between border-b border-gray-100 py-3 last:border-0">
                    <div>
                        <a href="{{ route('casos.show', $caso) }}" class="font-medium text-primary-600 hover:underline">
                            {{ $caso->numero_expediente }}
                        </a>
                        <p class="mt-0.5 text-xs text-gray-500">{{ $caso->descripcion }}</p>
                    </div>
                    <span class="badge-blue">{{ $caso->estado->label() }}</span>
                </div>
            @empty
                <p class="empty-state py-8">El cliente aún no tiene casos registrados.</p>
            @endforelse
        </div>
    </div>
@endsection