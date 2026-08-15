<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Maneja el registro de nuevos usuarios.
 */
class RegisteredUserController extends Controller
{
    /**
     * Muestra el formulario de registro.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Crea el usuario y lo autentica en la sesión.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        User::create($request->validated());

        Auth::login(User::where('email', $request->email)->firstOrFail());

        return redirect()->route('dashboard');
    }
}
