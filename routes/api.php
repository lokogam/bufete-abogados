<?php

use App\Http\Controllers\Api\AbogadoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CasoController;
use App\Http\Controllers\Api\ClienteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/user', fn (Request $request) => $request->user())->name('api.user');

    Route::apiResource('clientes', ClienteController::class)->names('api.clientes');
    Route::apiResource('abogados', AbogadoController::class)->names('api.abogados');
    Route::apiResource('casos', CasoController::class)->names('api.casos');
});
