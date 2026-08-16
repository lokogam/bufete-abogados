@extends('layouts.app')

@section('title', 'Registro')

@section('content')
    <div class="mx-auto flex max-w-md flex-col justify-center py-12">
        <div class="card">
            <div class="text-center">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-navy text-gold">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v.364c.396.22.78.48 1.146.766L12 6.045 10.104 4.13c.366-.286.75-.545 1.146-.766V3a.75.75 0 0 1 .75-.75ZM4.288 6.867a.75.75 0 0 1 1.06-.053L12 12.42l6.652-5.606a.75.75 0 1 1 1.008 1.11l-7.06 5.95V21.75a.75.75 0 0 1-1.5 0v-7.876l-7.06-5.95a.75.75 0 0 1-.752-1.047Z" clip-rule="evenodd" />
                    </svg>
                </span>
                <h1 class="mt-4 font-display text-2xl font-bold tracking-tight text-ink">Crear cuenta</h1>
                <p class="mt-1 text-sm text-muted">Regístrate para acceder al sistema.</p>
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

            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="name" class="label">Nombre</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="input">
                </div>

                <div>
                    <label for="email" class="label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="input">
                </div>

                <div>
                    <label for="password" class="label">Contraseña</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password" class="input">
                </div>

                <div>
                    <label for="password_confirmation" class="label">Confirmar contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="input">
                </div>

                <button type="submit" class="btn-primary w-full">
                    Crear cuenta
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-muted">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="link-action">Inicia sesión</a>
            </p>
        </div>
    </div>
@endsection