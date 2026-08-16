@extends('layouts.app')

@section('title', "Abogado · {$abogado->nombre_completo}")

@section('content')
    <a href="{{ route('abogados.index') }}" class="back-link">← Volver a abogados</a>

    <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl font-bold tracking-tight text-ink">{{ $abogado->nombre_completo }}</h1>
            <p class="mt-1 text-sm text-muted">
                <span class="font-medium" style="color: {{ $abogado->especialidad_color }}">{{ $abogado->especialidad ?? 'Sin especialidad' }}</span>
                · Cédula <span class="font-mono">{{ $abogado->cedula }}</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('abogados.edit', $abogado) }}" class="btn-secondary">Editar</a>
            <form method="POST" action="{{ route('abogados.destroy', $abogado) }}"
                onsubmit="return confirm('¿Eliminar este abogado? Esta acción es reversible.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn bg-danger-600 text-white hover:bg-danger-700">Eliminar</button>
            </form>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="card">
            <h2 class="card-title">Información profesional</h2>
            <dl class="detail-list mt-4">
                <div class="detail-item">
                    <dt>Nombre completo</dt>
                    <dd>{{ $abogado->nombre_completo }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Cédula</dt>
                    <dd class="font-mono">{{ $abogado->cedula }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Especialidad</dt>
                    <dd class="font-medium" style="color: {{ $abogado->especialidad_color }}">{{ $abogado->especialidad ?? '—' }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Email</dt>
                    <dd>{{ $abogado->email ?? '—' }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Teléfono</dt>
                    <dd class="font-mono">{{ $abogado->telefono ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Casos asignados ({{ $abogado->casos->count() }})</h2>
                <a href="{{ route('casos.create') }}" class="link-action">Nuevo caso</a>
            </div>
            @forelse ($abogado->casos as $caso)
                <div class="flex items-center justify-between border-b border-line/60 py-3 last:border-0">
                    <div>
                        <a href="{{ route('casos.show', $caso) }}" class="font-mono text-sm font-medium text-link hover:underline">
                            {{ $caso->numero_expediente }}
                        </a>
                        <p class="mt-0.5 text-xs text-muted">
                            {{ $caso->cliente->nombre_completo }} · Asignado {{ $caso->pivot?->fecha_asignacion?->format('d/m/Y') }}
                        </p>
                    </div>
                    <span class="badge {{ $caso->estado->badgeClass() }}">{{ $caso->estado->label() }}</span>
                </div>
            @empty
                <p class="empty-state py-8">El abogado aún no tiene casos asignados.</p>
            @endforelse
        </div>
    </div>
@endsection