@php
    $caso = $caso ?? null;
    $abogadosSeleccionados = $caso?->abogados?->pluck('id')->all() ?? old('abogados', []);
@endphp

@csrf

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label for="numero_expediente" class="label">Número de expediente</label>
        <input type="text" id="numero_expediente" name="numero_expediente" value="{{ old('numero_expediente', $caso?->numero_expediente) }}" required
            placeholder="Ej. EXP-0001-2024"
            class="input">
        @error('numero_expediente')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="cliente_id" class="label">Cliente</label>
        <select id="cliente_id" name="cliente_id" required
            class="input">
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
        <label for="fecha_inicio" class="label">Fecha de inicio</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $caso?->fecha_inicio?->format('Y-m-d')) }}" required
            class="input">
        @error('fecha_inicio')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="fecha_finalizacion" class="label">Fecha de finalización</label>
        <input type="date" id="fecha_finalizacion" name="fecha_finalizacion" value="{{ old('fecha_finalizacion', $caso?->fecha_finalizacion?->format('Y-m-d')) }}"
            class="input">
        @error('fecha_finalizacion')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="estado" class="label">Estado</label>
        <select id="estado" name="estado" required
            class="input">
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
        <label for="descripcion" class="label">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3"
            class="input">{{ old('descripcion', $caso?->descripcion) }}</textarea>
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
            <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-line bg-surface px-3 py-2.5 hover:border-gold hover:bg-soft/50">
                <input type="checkbox" name="abogados[]" value="{{ $abogado->id }}"
                    {{ in_array($abogado->id, $abogadosSeleccionados, true) ? 'checked' : '' }}
                    class="rounded border-line text-navy focus:ring-gold/50">
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