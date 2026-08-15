<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CasoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/user', fn (Request $request) => $request->user())->name('api.user');

    Route::get('/casos/{caso}', [CasoController::class, 'show'])->name('api.casos.show');
});
