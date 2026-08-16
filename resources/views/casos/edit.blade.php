@extends('layouts.app')

@section('title', "Editar caso · {$caso->numero_expediente}")

@section('content')
    <a href="{{ route('casos.show', $caso) }}" class="back-link">← Volver al caso</a>

    <div class="mt-4">
        <h1 class="font-display text-3xl font-bold tracking-tight text-ink">Editar caso</h1>
        <p class="mt-1 text-sm text-muted">{{ $caso->numero_expediente }}</p>
    </div>

    <div class="card mt-6">
        <form method="POST" action="{{ route('casos.update', $caso) }}" class="space-y-4">
            @method('PUT')
            @include('casos._form', ['caso' => $caso])
            <div class="flex items-center justify-end gap-3 border-t border-line/60 pt-4">
                <a href="{{ route('casos.show', $caso) }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Actualizar caso</button>
            </div>
        </form>
    </div>
@endsection