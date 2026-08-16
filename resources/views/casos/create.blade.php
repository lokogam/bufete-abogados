@extends('layouts.app')

@section('title', 'Nuevo caso')

@section('content')
    <a href="{{ route('casos.index') }}" class="back-link">← Volver a casos</a>

    <div class="mt-4">
        <h1 class="font-display text-3xl font-bold tracking-tight text-ink">Nuevo caso</h1>
        <p class="mt-1 text-sm text-muted">Registra un nuevo expediente judicial.</p>
    </div>

    <div class="card mt-6">
        <form method="POST" action="{{ route('casos.store') }}" class="space-y-4">
            @include('casos._form')
            <div class="flex items-center justify-end gap-3 border-t border-line/60 pt-4">
                <a href="{{ route('casos.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Guardar caso</button>
            </div>
        </form>
    </div>
@endsection