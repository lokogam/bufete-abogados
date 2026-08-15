<?php

use App\Http\Controllers\AbogadoController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CasoWebController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/exportar-excel', [ExportController::class, 'download'])->name('casos.export');

    Route::resource('clientes', ClienteController::class);
    Route::resource('abogados', AbogadoController::class);
    Route::resource('casos', CasoWebController::class);
});

$docsUrl = config('scribe.laravel.docs_url', '/docs');

Route::view($docsUrl, 'scribe.index')->name('scribe');

Route::get("{$docsUrl}.postman", function (): JsonResponse {
    return new JsonResponse(Storage::disk('local')->get('scribe/collection.json'), json: true);
})->name('scribe.postman');
