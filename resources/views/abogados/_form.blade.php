@php
    $abogado = $abogado ?? null;
@endphp

@csrf

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label for="cedula" class="label">Cédula</label>
        <input type="text" id="cedula" name="cedula" value="{{ old('cedula', $abogado?->cedula) }}" required
            class="input">
        @error('cedula')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="especialidad" class="label">Especialidad</label>
        <input type="text" id="especialidad" name="especialidad" value="{{ old('especialidad', $abogado?->especialidad) }}"
            placeholder="Ej. Derecho Civil"
            class="input">
        @error('especialidad')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="nombre" class="label">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $abogado?->nombre) }}" required
            class="input">
        @error('nombre')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="apellido" class="label">Apellido</label>
        <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $abogado?->apellido) }}" required
            class="input">
        @error('apellido')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="label">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $abogado?->email) }}"
            class="input">
        @error('email')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="telefono" class="label">Teléfono</label>
        <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $abogado?->telefono) }}"
            class="input">
        @error('telefono')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>
</div>