@extends('layouts.app')

@section('title', 'Nuevo abogado')

@section('content')
    <a href="{{ route('abogados.index') }}" class="back-link">← Volver a abogados</a>

    <div class="mt-4">
        <h1 class="text-2xl font-bold">Nuevo abogado</h1>
        <p class="mt-1 text-sm text-gray-500">Registra un nuevo abogado del bufete.</p>
    </div>

    <div class="card mt-6 max-w-2xl">
        <form method="POST" action="{{ route('abogados.store') }}" class="space-y-4">
            @include('abogados._form')
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('abogados.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Guardar abogado</button>
            </div>
        </form>
    </div>
@endsection