<?php

use App\Http\Controllers\CasoWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::get('/casos', [CasoWebController::class, 'index'])->name('casos.index');
Route::get('/casos/{caso}', [CasoWebController::class, 'show'])->name('casos.show');

Route::get('/exportar-excel', [ExportController::class, 'download'])->name('casos.export');
