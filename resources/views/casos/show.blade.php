@extends('layouts.app')

@section('title', "Caso {$caso->numero_expediente}")

@section('content')
    <a href="{{ route('casos.index') }}" class="back-link">← Volver a casos</a>

    <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-mono text-2xl font-bold tracking-tight text-ink">{{ $caso->numero_expediente }}</h1>
            <p class="mt-1 text-sm text-muted">{{ $caso->descripcion }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="badge {{ $caso->estado->badgeClass() }}">{{ $caso->estado->label() }}</span>
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
        <div class="card lg:col-span-2">
            <h2 class="card-title">Información del caso</h2>
            <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="detail-item">
                    <dt>Fecha de inicio</dt>
                    <dd class="font-mono">{{ $caso->fecha_inicio?->format('d/m/Y') }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Fecha de finalización</dt>
                    <dd class="font-mono">{{ $caso->fecha_finalizacion?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div class="detail-item sm:col-span-2">
                    <dt>Descripción</dt>
                    <dd>{{ $caso->descripcion ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="card">
            <h2 class="card-title">Cliente</h2>
            <dl class="detail-list mt-4">
                <div class="detail-item">
                    <dt>Nombre</dt>
                    <dd>{{ $caso->cliente->nombre_completo }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Cédula</dt>
                    <dd class="font-mono">{{ $caso->cliente->cedula }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Email</dt>
                    <dd>{{ $caso->cliente->email ?? '—' }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Teléfono</dt>
                    <dd class="font-mono">{{ $caso->cliente->telefono ?? '—' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="card mt-6">
        <h2 class="card-title">Abogados asignados ({{ $caso->abogados->count() }})</h2>
        <ul class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($caso->abogados as $abogado)
                <li class="rounded-lg border border-line bg-soft/60 p-3">
                    <p class="text-sm font-semibold text-ink">{{ $abogado->nombre_completo }}</p>
                    <p class="mt-1 text-xs font-medium" style="color: {{ $abogado->especialidad_color }}">{{ $abogado->especialidad }}</p>
                    <p class="mt-1 text-xs text-muted">
                        Asignado: {{ $abogado->pivot?->fecha_asignacion?->format('d/m/Y') }}
                    </p>
                </li>
            @endforeach
        </ul>
    </div>
@endsection