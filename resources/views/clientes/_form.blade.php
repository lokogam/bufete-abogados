@php
    $cliente = $cliente ?? null;
@endphp

@csrf

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label for="cedula" class="block text-sm font-medium text-gray-700">Cédula</label>
        <input type="text" id="cedula" name="cedula" value="{{ old('cedula', $cliente?->cedula) }}" required
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
        @error('cedula')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $cliente?->nombre) }}" required
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
        @error('nombre')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido</label>
        <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $cliente?->apellido) }}" required
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
        @error('apellido')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $cliente?->email) }}"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
        @error('email')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
        <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $cliente?->telefono) }}"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
        @error('telefono')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
        <input type="text" id="direccion" name="direccion" value="{{ old('direccion', $cliente?->direccion) }}"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
        @error('direccion')
            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>
</div>