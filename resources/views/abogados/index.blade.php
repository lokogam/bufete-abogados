@extends('layouts.app')

@section('title', 'Abogados')

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Abogados</h1>
            <p class="mt-1 text-sm text-gray-500">{{ count($abogados) }} abogados registrados.</p>
        </div>
        <a href="{{ route('abogados.create') }}" class="btn-primary">
            Nuevo abogado
        </a>
    </div>

    @if (session('success'))
        <div class="mt-6 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-600">
            {{ session('success') }}
        </div>
    @endif

    <div class="data-table mt-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Especialidad</th>
                    <th>Email</th>
                    <th>Casos</th>
                    <th class="cell-actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($abogados as $abogado)
                    <tr>
                        <td>{{ $abogado->cedula }}</td>
                        <td class="font-medium">{{ $abogado->nombre_completo }}</td>
                        <td>{{ $abogado->especialidad ?? '—' }}</td>
                        <td>{{ $abogado->email ?? '—' }}</td>
                        <td>
                            <span class="badge-gray">{{ $abogado->casos_count }} casos</span>
                        </td>
                        <td class="cell-actions">
                            <a href="{{ route('abogados.show', $abogado) }}" class="link-action">Ver</a>
                            <a href="{{ route('abogados.edit', $abogado) }}" class="link-action ml-3">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-gray-400">No hay abogados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection