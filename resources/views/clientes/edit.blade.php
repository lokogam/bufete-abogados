@extends('layouts.app')

@section('title', "Editar cliente · {$cliente->nombre_completo}")

@section('content')
    <a href="{{ route('clientes.show', $cliente) }}" class="back-link">← Volver al cliente</a>

    <div class="mt-4">
        <h1 class="font-display text-3xl font-bold tracking-tight text-ink">Editar cliente</h1>
        <p class="mt-1 text-sm text-muted">{{ $cliente->nombre_completo }}</p>
    </div>

    <div class="card mt-6 max-w-2xl">
        <form method="POST" action="{{ route('clientes.update', $cliente) }}" class="space-y-4">
            @method('PUT')
            @include('clientes._form', ['cliente' => $cliente])
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('clientes.show', $cliente) }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Actualizar cliente</button>
            </div>
        </form>
    </div>
@endsection