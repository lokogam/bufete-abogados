@extends('layouts.app')

@section('title', 'Nuevo cliente')

@section('content')
    <a href="{{ route('clientes.index') }}" class="back-link">← Volver a clientes</a>

    <div class="mt-4">
        <h1 class="font-display text-3xl font-bold tracking-tight text-ink">Nuevo cliente</h1>
        <p class="mt-1 text-sm text-muted">Registra un nuevo cliente del bufete.</p>
    </div>

    <div class="card mt-6 max-w-2xl">
        <form method="POST" action="{{ route('clientes.store') }}" class="space-y-4">
            @include('clientes._form')
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('clientes.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Guardar cliente</button>
            </div>
        </form>
    </div>
@endsection