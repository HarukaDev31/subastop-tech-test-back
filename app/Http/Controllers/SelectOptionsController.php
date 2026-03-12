<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Especie;
use App\Models\Habitante;
use App\Models\Nave;
use App\Models\Pelicula;
use App\Models\Planeta;
use App\Models\Vehiculo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

/**
 * Endpoints for populating select/dropdown options (id + label).
 * Responses are cached to avoid repeated DB queries; no pagination.
 */
class SelectOptionsController extends Controller
{
    private const CACHE_TTL_SECONDS = 600; // 10 minutes

    public function planetas(): JsonResponse
    {
        $data = Cache::remember('select.planetas', self::CACHE_TTL_SECONDS, function () {
            return Planeta::orderBy('nombre')->get(['id', 'nombre'])->map(fn ($p) => [
                'id'   => $p->id,
                'name' => $p->nombre,
            ])->values()->all();
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function especies(): JsonResponse
    {
        $data = Cache::remember('select.especies', self::CACHE_TTL_SECONDS, function () {
            return Especie::orderBy('nombre')->get(['id', 'nombre'])->map(fn ($e) => [
                'id'   => $e->id,
                'name' => $e->nombre,
            ])->values()->all();
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function habitantes(): JsonResponse
    {
        $data = Cache::remember('select.habitantes', self::CACHE_TTL_SECONDS, function () {
            return Habitante::orderBy('nombre')->get(['id', 'nombre'])->map(fn ($h) => [
                'id'   => $h->id,
                'name' => $h->nombre,
            ])->values()->all();
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function peliculas(): JsonResponse
    {
        $data = Cache::remember('select.peliculas', self::CACHE_TTL_SECONDS, function () {
            return Pelicula::orderBy('titulo')->get(['id', 'titulo'])->map(fn ($p) => [
                'id'    => $p->id,
                'title' => $p->titulo,
            ])->values()->all();
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function naves(): JsonResponse
    {
        $data = Cache::remember('select.naves', self::CACHE_TTL_SECONDS, function () {
            return Nave::orderBy('nombre')->get(['id', 'nombre'])->map(fn ($n) => [
                'id'   => $n->id,
                'name' => $n->nombre,
            ])->values()->all();
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function vehiculos(): JsonResponse
    {
        $data = Cache::remember('select.vehiculos', self::CACHE_TTL_SECONDS, function () {
            return Vehiculo::orderBy('nombre')->get(['id', 'nombre'])->map(fn ($v) => [
                'id'   => $v->id,
                'name' => $v->nombre,
            ])->values()->all();
        });

        return response()->json(['success' => true, 'data' => $data]);
    }
}
