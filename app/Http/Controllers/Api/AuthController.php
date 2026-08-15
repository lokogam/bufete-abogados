<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    /**
     * Autentica al usuario y devuelve un token Bearer de acceso.
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
}
