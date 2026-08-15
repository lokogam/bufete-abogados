@php
    $caso = $caso ?? null;
    $abogadosSeleccionados = $caso?->abogados?->pluck('id')->all() ?? old('abogados', []);
@endphp

@csrf

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label for="numero_expediente" class="block text-sm font-medium text-gray-700">Número de expediente</label>
        <input type="text" id="numero_expediente" name="numero_expediente" value="{{ old('numero_expediente', $caso?->numero_expediente) }}" required
            placeholder="Ej. EXP-0001-2024"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
        @error('numero_expediente')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente</label>
        <select id="cliente_id" name="cliente_id" required
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
            <option value="" disabled {{ old('cliente_id', $caso?->cliente_id) === null ? 'selected' : '' }}>Selecciona un cliente…</option>
            @foreach ($clientes as $cliente)
                <option value="{{ $cliente->id }}" {{ (string) old('cliente_id', $caso?->cliente_id) === (string) $cliente->id ? 'selected' : '' }}>
                    {{ $cliente->nombre_completo }} ({{ $cliente->cedula }})
                </option>
            @endforeach
        </select>
        @error('cliente_id')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha de inicio</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $caso?->fecha_inicio?->format('Y-m-d')) }}" required
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
        @error('fecha_inicio')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="fecha_finalizacion" class="block text-sm font-medium text-gray-700">Fecha de finalización</label>
        <input type="date" id="fecha_finalizacion" name="fecha_finalizacion" value="{{ old('fecha_finalizacion', $caso?->fecha_finalizacion?->format('Y-m-d')) }}"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
        @error('fecha_finalizacion')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
        <select id="estado" name="estado" required
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
            @foreach ($estados as $value => $label)
                <option value="{{ $value }}" {{ old('estado', $caso?->estado?->value) === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('estado')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">{{ old('descripcion', $caso?->descripcion) }}</textarea>
        @error('descripcion')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-6">
    <h3 class="text-sm font-semibold text-gray-700">Abogados asignados</h3>
    <p class="mt-1 text-xs text-gray-500">Selecciona los abogados que intervienen en este caso.</p>
    <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
        @forelse ($abogados as $abogado)
            <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 bg-white px-3 py-2.5 hover:border-primary-300 hover:bg-primary-50/50">
                <input type="checkbox" name="abogados[]" value="{{ $abogado->id }}"
                    {{ in_array($abogado->id, $abogadosSeleccionados, true) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-primary-600 focus:ring-primary-200">
                <span>
                    <span class="block text-sm font-medium text-gray-800">{{ $abogado->nombre_completo }}</span>
                    <span class="block text-xs text-gray-500">{{ $abogado->especialidad }}</span>
                </span>
            </label>
        @empty
            <p class="text-sm text-gray-400">No hay abogados disponibles. Crea uno primero.</p>
        @endforelse
    </div>
    @error('abogados')
        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
    @enderror
</div>