@php
    $cliente = $cliente ?? null;
@endphp

@csrf

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label for="cedula" class="label">Cédula</label>
        <input type="text" id="cedula" name="cedula" value="{{ old('cedula', $cliente?->cedula) }}" required
            class="input">
        @error('cedula')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="nombre" class="label">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $cliente?->nombre) }}" required
            class="input">
        @error('nombre')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="apellido" class="label">Apellido</label>
        <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $cliente?->apellido) }}" required
            class="input">
        @error('apellido')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="label">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $cliente?->email) }}"
            class="input">
        @error('email')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="telefono" class="label">Teléfono</label>
        <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $cliente?->telefono) }}"
            class="input">
        @error('telefono')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="direccion" class="label">Dirección</label>
        <input type="text" id="direccion" name="direccion" value="{{ old('direccion', $cliente?->direccion) }}"
            class="input">
        @error('direccion')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>
</div>