<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Servicio de autenticación de la API (emisión de tokens Bearer).
 */
final class AuthService
{
    /**
     * Valida las credenciales y emite un token de acceso personal.
     *
     * @param  array{email: string, password: string}  $credentials
     * @return array{token: string, user: User}
     *
     * @throws InvalidCredentialsException
     */
    public function issueToken(array $credentials): array
    {
        if (! Auth::attempt($credentials)) {
            throw new InvalidCredentialsException;
        }

        /** @var User $user */
        $user = Auth::user();

        $token = $user->createToken('api-access')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    /**
     * Crea un usuario y emite un token de acceso personal.
     *
     * @param  array{name: string, email: string, password: string}  $data
     * @return array{token: string, user: User}
     */
    public function register(array $data): array
    {
        /** @var User $user */
        $user = User::create($data);

        $token = $user->createToken('api-access')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    /**
     * Revoca el token de acceso actual del usuario.
     */
    public function revokeCurrentToken(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
