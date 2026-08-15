@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-2xl font-bold">Panel de control</h1>
    <p class="mt-1 text-sm text-gray-500">Resumen general del bufete.</p>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Casos en trámite</p>
            <p class="mt-1 text-3xl font-bold text-blue-600">{{ $casosEnTramite }}</p>
        </div>
        <div class="rounded-lg bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Casos archivados</p>
            <p class="mt-1 text-3xl font-bold text-gray-700">{{ $casosArchivados }}</p>
        </div>
        <div class="rounded-lg bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Clientes</p>
            <p class="mt-1 text-3xl font-bold text-emerald-600">{{ $totalClientes }}</p>
        </div>
        <div class="rounded-lg bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Abogados</p>
            <p class="mt-1 text-3xl font-bold text-purple-600">{{ $totalAbogados }}</p>
        </div>
    </div>

    <div class="mt-8">
        <a href="{{ route('casos.index') }}" class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
            Ver todos los casos →
        </a>
    </div>
@endsection