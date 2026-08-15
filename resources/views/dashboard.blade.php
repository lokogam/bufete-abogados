@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-2xl font-bold">Panel de control</h1>
    <p class="mt-1 text-sm text-gray-500">Resumen general del bufete.</p>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('casos.index') }}" class="stat-card blue">
            <p class="stat-label">Casos en trámite</p>
            <p class="stat-value text-primary-600">{{ $casosEnTramite }}</p>
        </a>
        <a href="{{ route('casos.index') }}" class="stat-card gray">
            <p class="stat-label">Casos archivados</p>
            <p class="stat-value text-gray-700">{{ $casosArchivados }}</p>
        </a>
        <a href="{{ route('clientes.index') }}" class="stat-card green">
            <p class="stat-label">Clientes</p>
            <p class="stat-value text-success-600">{{ $totalClientes }}</p>
        </a>
        <a href="{{ route('abogados.index') }}" class="stat-card purple">
            <p class="stat-label">Abogados</p>
            <p class="stat-value text-purple-600">{{ $totalAbogados }}</p>
        </a>
    </div>

    <div class="mt-8 flex flex-wrap items-center gap-3">
        <a href="{{ route('casos.index') }}" class="btn-primary">Ver casos</a>
        <a href="{{ route('clientes.index') }}" class="btn-secondary">Ver clientes</a>
        <a href="{{ route('abogados.index') }}" class="btn-secondary">Ver abogados</a>
        <a href="{{ route('casos.export') }}" class="btn-ghost">Exportar Excel</a>
    </div>
@endsection