<?php

declare(strict_types=1);

use App\Interfaces\Http\Local\EspecieController;
use App\Interfaces\Http\Local\HabitanteController;
use App\Interfaces\Http\Local\NaveController;
use App\Interfaces\Http\Local\PeliculaController;
use App\Interfaces\Http\Local\PlanetaController;
use App\Http\Controllers\SelectOptionsController;
use App\Interfaces\Http\Local\VehiculoController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// Select options (all records, no pagination, cached)
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/select/planetas',   [SelectOptionsController::class, 'planetas'])->name('select.planetas');
Route::get('/select/especies',   [SelectOptionsController::class, 'especies'])->name('select.especies');
Route::get('/select/habitantes', [SelectOptionsController::class, 'habitantes'])->name('select.habitantes');
Route::get('/select/peliculas',  [SelectOptionsController::class, 'peliculas'])->name('select.peliculas');
Route::get('/select/naves',      [SelectOptionsController::class, 'naves'])->name('select.naves');
Route::get('/select/vehiculos',  [SelectOptionsController::class, 'vehiculos'])->name('select.vehiculos');

// ─────────────────────────────────────────────────────────────────────────────
// Local DB – CRUD resources (English response keys)
// ─────────────────────────────────────────────────────────────────────────────
Route::apiResource('planetas',  PlanetaController::class);
Route::apiResource('peliculas', PeliculaController::class);
Route::apiResource('naves',     NaveController::class);
Route::apiResource('especies',  EspecieController::class);
Route::apiResource('vehiculos', VehiculoController::class);

Route::apiResource('habitantes', HabitanteController::class);
