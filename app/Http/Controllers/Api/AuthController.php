<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    /**
     * @group Autenticación
     *
     * Autentica al usuario y devuelve un token Bearer de acceso.
     *
     * @unauthenticated
     *
     * @bodyParam email string required Email del usuario. Example: demo@bufete.com
     * @bodyParam password string required Contraseña del usuario. Example: password
     *
     * @responseField token string Token Bearer para autenticar las siguientes peticiones.
     * @responseField token_type string Tipo de token, siempre "Bearer".
     * @responseField user object Datos públicos del usuario autenticado.
     *
     * @response status=200 scenario="Credenciales válidas" {
     *   "message": "Autenticación exitosa.",
     *   "token": "1|xxxxxxxxxxxxxxxxxxxx",
     *   "token_type": "Bearer",
     *   "user": {
     *     "id": 1,
     *     "name": "Demo Bufete",
     *     "email": "demo@bufete.com"
     *   }
     * }
     * @response status=401 scenario="Credenciales inválidas" {
     *   "message": "Credenciales inválidas."
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->issueToken($request->validated());
        } catch (InvalidCredentialsException) {
            return response()->json([
                'message' => 'Credenciales inválidas.',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'message' => 'Autenticación exitosa.',
            'token' => $result['token'],
            'token_type' => 'Bearer',
            'user' => $result['user']->only('id', 'name', 'email'),
        ]);
    }

    /**
     * @group Autenticación
     *
     * Registra un nuevo usuario y devuelve un token Bearer de acceso.
     *
     * @unauthenticated
     *
     * @bodyParam name string required Nombre completo del usuario. Example: Juan Pérez
     * @bodyParam email string required Email del usuario. Example: juan@example.com
     * @bodyParam password string required Contraseña (mínimo 8 caracteres). Example: secret123
     * @bodyParam password_confirmation string required Confirmación de la contraseña. Example: secret123
     *
     * @responseField token string Token Bearer para autenticar las siguientes peticiones.
     *
     * @response status=201 scenario="Registro exitoso" {
     *   "message": "Registro exitoso.",
     *   "token": "1|xxxxxxxxxxxxxxxxxxxx",
     *   "token_type": "Bearer",
     *   "user": {
     *     "id": 2,
     *     "name": "Juan Pérez",
     *     "email": "juan@example.com"
     *   }
     * }
     * @response status=422 scenario="Datos inválidos" {
     *   "message": "The email has already been taken."
     * }
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'Registro exitoso.',
            'token' => $result['token'],
            'token_type' => 'Bearer',
            'user' => $result['user']->only('id', 'name', 'email'),
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * @group Autenticación
     *
     * Cierra la sesión revocando el token Bearer actual.
     *
     * @authenticated
     *
     * @response status=200 {
     *   "message": "Sesión cerrada correctamente."
     * }
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->revokeCurrentToken($request->user());

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }
}
