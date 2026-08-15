@extends('layouts.app')

@section('title', 'Casos')

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Casos</h1>
            <p class="mt-1 text-sm text-gray-500">Expedientes registrados en orden ascendente.</p>
        </div>
        <a href="{{ route('casos.export') }}" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
            Exportar Excel
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Expediente</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Cliente</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Estado</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Inicio</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Finalización</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Abogados</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($casos as $caso)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $caso->numero_expediente }}</td>
                        <td class="px-4 py-3">{{ $caso->cliente->nombre_completo }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                {{ $caso->estado->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $caso->fecha_inicio?->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $caso->fecha_finalizacion?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $caso->abogados->pluck('nombre_completo')->implode(', ') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('casos.show', $caso) }}" class="font-medium text-blue-600 hover:underline">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">No hay casos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection