<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\AssuntoController;
use App\Http\Controllers\RelatorioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rota inicial redireciona para a lista de livros
Route::get('/', function () {
    return redirect()->route('livros.index');
});

// Rotas de gerenciamento de livros
Route::resource('livros', LivroController::class)->except(['show']);
Route::resource('autores', AutorController::class);
Route::resource('assuntos', AssuntoController::class);

// Rotas de relatórios
Route::prefix('relatorios')->name('relatorios.')->group(function () {
    Route::get('livros-por-autor', [RelatorioController::class, 'livrosPorAutor'])
        ->name('livros-por-autor');
        
    // Rota para exportação de relatórios
    Route::get('exportar/{tipo}', [RelatorioController::class, 'exportar'])
        ->name('exportar');
});
