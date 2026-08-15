@extends('layouts.app')

@section('title', "Caso {$caso->numero_expediente}")

@section('content')
    <a href="{{ route('casos.index') }}" class="text-sm text-gray-500 hover:text-gray-900">← Volver a casos</a>

    <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ $caso->numero_expediente }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $caso->descripcion }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="badge-blue">{{ $caso->estado->label() }}</span>
            <a href="{{ route('casos.edit', $caso) }}" class="btn-secondary btn-sm">Editar</a>
            <form method="POST" action="{{ route('casos.destroy', $caso) }}"
                onsubmit="return confirm('¿Eliminar este caso? Esta acción es reversible.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm bg-danger-600 text-white hover:bg-danger-700">Eliminar</button>
            </form>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-lg bg-white p-5 shadow-sm lg:col-span-2">
            <h2 class="text-lg font-semibold">Información del caso</h2>
            <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-400">Fecha de inicio</dt>
                    <dd class="mt-1">{{ $caso->fecha_inicio?->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-400">Fecha de finalización</dt>
                    <dd class="mt-1">{{ $caso->fecha_finalizacion?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium uppercase text-gray-400">Descripción</dt>
                    <dd class="mt-1">{{ $caso->descripcion ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-lg bg-white p-5 shadow-sm">
            <h2 class="text-lg font-semibold">Cliente</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-400">Nombre</dt>
                    <dd class="mt-1 font-medium">{{ $caso->cliente->nombre_completo }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-400">Cédula</dt>
                    <dd class="mt-1">{{ $caso->cliente->cedula }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-400">Email</dt>
                    <dd class="mt-1">{{ $caso->cliente->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-400">Teléfono</dt>
                    <dd class="mt-1">{{ $caso->cliente->telefono ?? '—' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-6 rounded-lg bg-white p-5 shadow-sm">
        <h2 class="text-lg font-semibold">Abogados asignados ({{ $caso->abogados->count() }})</h2>
        <ul class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($caso->abogados as $abogado)
                <li class="rounded-md border border-gray-100 bg-gray-50 p-3">
                    <p class="font-medium">{{ $abogado->nombre_completo }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $abogado->especialidad }}</p>
                    <p class="mt-1 text-xs text-gray-400">
                        Asignado: {{ $abogado->pivot?->fecha_asignacion?->format('d/m/Y') }}
                    </p>
                </li>
            @endforeach
        </ul>
    </div>
@endsection