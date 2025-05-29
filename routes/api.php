<?php

use App\Http\Controllers\Api\V1\AssuntoController;
use App\Http\Controllers\Api\V1\AutorController;
use App\Http\Controllers\Api\V1\LivroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas da API versão 1
Route::prefix('v1')->group(function () {
    // Rotas de Autores
    Route::apiResource('autores', AutorController::class)->only([
        'index', 'store', 'show', 'update', 'destroy'
    ]);

    // Rotas de Assuntos
    Route::apiResource('assuntos', AssuntoController::class)->only([
        'index', 'store', 'show', 'update', 'destroy'
    ]);

    // Rotas de Livros
    Route::apiResource('livros', LivroController::class)->only([
        'index', 'store', 'show', 'update', 'destroy'
    ]);
    
    // Rotas de Relatórios
    Route::prefix('relatorios')->group(function () {
        Route::get('livros-por-autor', [\App\Http\Controllers\Api\V1\ReportController::class, 'livrosPorAutorPdf'])
            ->name('relatorios.livros-por-autor');
    });
});
