<?php

declare(strict_types=1);

use App\Http\Controllers\SwapiController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// SWAPI – External API (read-only, cached, Spanish DTOs)
// ─────────────────────────────────────────────────────────────────────────────
Route::prefix('swapi')->name('swapi.')->group(function (): void {
    Route::get('/personajes',      [SwapiController::class, 'personajes'])->name('personajes');
    Route::get('/personajes/{id}', [SwapiController::class, 'personaje'])->name('personaje');

    Route::get('/planetas',        [SwapiController::class, 'planetasSwapi'])->name('planetas');
    Route::get('/planetas/{id}',   [SwapiController::class, 'planetaSwapi'])->name('planeta');

    Route::get('/naves',           [SwapiController::class, 'naves'])->name('naves');
    Route::get('/naves/{id}',      [SwapiController::class, 'nave'])->name('nave');

    Route::get('/peliculas',       [SwapiController::class, 'peliculas'])->name('peliculas');
    Route::get('/peliculas/{id}',  [SwapiController::class, 'pelicula'])->name('pelicula');

    Route::get('/especies',        [SwapiController::class, 'especies'])->name('especies');
    Route::get('/especies/{id}',   [SwapiController::class, 'especie'])->name('especie');

    Route::get('/vehiculos',       [SwapiController::class, 'vehiculos'])->name('vehiculos');
    Route::get('/vehiculos/{id}',  [SwapiController::class, 'vehiculo'])->name('vehiculo');
});
