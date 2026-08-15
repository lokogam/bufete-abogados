@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
    <div class="mx-auto flex max-w-md flex-col justify-center py-12">
        <div class="card">
            <div class="text-center">
                <span class="text-4xl">⚖️</span>
                <h1 class="mt-4 text-2xl font-bold text-gray-900">Iniciar sesión</h1>
                <p class="mt-1 text-sm text-gray-500">Accede al sistema del bufete de abogados.</p>
            </div>

            @if ($errors->any())
                <div class="mt-6 rounded-lg border border-danger-200 bg-danger-50 px-4 py-3 text-sm text-danger-600">
                    <ul class="list-disc space-y-1 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-200">
                        Recordarme
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full">
                    Iniciar sesión
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="link-action">Regístrate</a>
            </p>
        </div>
    </div>
@endsection