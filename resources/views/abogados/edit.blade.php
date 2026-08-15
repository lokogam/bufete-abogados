@extends('layouts.app')

@section('title', "Editar abogado · {$abogado->nombre_completo}")

@section('content')
    <a href="{{ route('abogados.show', $abogado) }}" class="back-link">← Volver al abogado</a>

    <div class="mt-4">
        <h1 class="text-2xl font-bold">Editar abogado</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $abogado->nombre_completo }}</p>
    </div>

    <div class="card mt-6 max-w-2xl">
        <form method="POST" action="{{ route('abogados.update', $abogado) }}" class="space-y-4">
            @method('PUT')
            @include('abogados._form', ['abogado' => $abogado])
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('abogados.show', $abogado) }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Actualizar abogado</button>
            </div>
        </form>
    </div>
@endsection