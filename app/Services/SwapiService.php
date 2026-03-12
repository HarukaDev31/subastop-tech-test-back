<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\EspecieDTO;
use App\DTOs\NaveDTO;
use App\DTOs\PeliculaDTO;
use App\DTOs\PersonajeDTO;
use App\DTOs\PlanetaSwapiDTO;
use App\DTOs\VehiculoDTO;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SwapiService
{
    private const BASE_URL    = 'https://swapi.py4e.com/api';
    private const CACHE_TTL   = 3600; // 1 hora
    private const RETRY_TIMES = 3;
    private const RETRY_SLEEP = 500;  // ms
    private const TIMEOUT     = 10;

    // -------------------------------------------------------------------------
    // Personajes
    // -------------------------------------------------------------------------

    public function listarPersonajes(int $pagina = 1): array
    {
        return $this->fetchPaginado("personajes_pagina_{$pagina}", "/people/?page={$pagina}", 'personajes', fn (array $item) => PersonajeDTO::fromArray($item)->toArray());
    }

    public function obtenerPersonaje(int $id): PersonajeDTO
    {
        $data = $this->fetchConCache("personaje_{$id}", "/people/{$id}/");

        Log::info('SWAPI: personaje obtenido', ['id' => $id, 'nombre' => $data['name'] ?? '']);

        return PersonajeDTO::fromArray($data);
    }

    // -------------------------------------------------------------------------
    // Planetas
    // -------------------------------------------------------------------------

    public function listarPlanetas(int $pagina = 1): array
    {
        return $this->fetchPaginado("planetas_pagina_{$pagina}", "/planets/?page={$pagina}", 'planetas', fn (array $item) => PlanetaSwapiDTO::fromArray($item)->toArray());
    }

    public function obtenerPlaneta(int $id): PlanetaSwapiDTO
    {
        $data = $this->fetchConCache("planeta_swapi_{$id}", "/planets/{$id}/");

        Log::info('SWAPI: planeta obtenido', ['id' => $id, 'nombre' => $data['name'] ?? '']);

        return PlanetaSwapiDTO::fromArray($data);
    }

    // -------------------------------------------------------------------------
    // Naves
    // -------------------------------------------------------------------------

    public function listarNaves(int $pagina = 1): array
    {
        return $this->fetchPaginado("naves_pagina_{$pagina}", "/starships/?page={$pagina}", 'naves', fn (array $item) => NaveDTO::fromArray($item)->toArray());
    }

    public function obtenerNave(int $id): NaveDTO
    {
        $data = $this->fetchConCache("nave_{$id}", "/starships/{$id}/");

        Log::info('SWAPI: nave obtenida', ['id' => $id, 'nombre' => $data['name'] ?? '']);

        return NaveDTO::fromArray($data);
    }

    // -------------------------------------------------------------------------
    // Películas
    // -------------------------------------------------------------------------

    public function listarPeliculas(): array
    {
        return $this->fetchPaginado('peliculas', '/films/', 'peliculas', fn (array $item) => PeliculaDTO::fromArray($item)->toArray());
    }

    public function obtenerPelicula(int $id): PeliculaDTO
    {
        $data = $this->fetchConCache("pelicula_{$id}", "/films/{$id}/");

        Log::info('SWAPI: película obtenida', ['id' => $id, 'titulo' => $data['title'] ?? '']);

        return PeliculaDTO::fromArray($data);
    }

    // -------------------------------------------------------------------------
    // Especies
    // -------------------------------------------------------------------------

    public function listarEspecies(int $pagina = 1): array
    {
        return $this->fetchPaginado("especies_pagina_{$pagina}", "/species/?page={$pagina}", 'especies', fn (array $item) => EspecieDTO::fromArray($item)->toArray());
    }

    public function obtenerEspecie(int $id): EspecieDTO
    {
        $data = $this->fetchConCache("especie_{$id}", "/species/{$id}/");

        Log::info('SWAPI: especie obtenida', ['id' => $id, 'nombre' => $data['name'] ?? '']);

        return EspecieDTO::fromArray($data);
    }

    // -------------------------------------------------------------------------
    // Vehículos
    // -------------------------------------------------------------------------

    public function listarVehiculos(int $pagina = 1): array
    {
        return $this->fetchPaginado("vehiculos_pagina_{$pagina}", "/vehicles/?page={$pagina}", 'vehiculos', fn (array $item) => VehiculoDTO::fromArray($item)->toArray());
    }

    public function obtenerVehiculo(int $id): VehiculoDTO
    {
        $data = $this->fetchConCache("vehiculo_{$id}", "/vehicles/{$id}/");

        Log::info('SWAPI: vehículo obtenido', ['id' => $id, 'nombre' => $data['name'] ?? '']);

        return VehiculoDTO::fromArray($data);
    }

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

    private function fetchConCache(string $cacheKey, string $endpoint): array
    {
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($cacheKey, $endpoint) {
            Log::info('SWAPI: cache miss — petición a red', ['key' => $cacheKey, 'endpoint' => $endpoint]);
            return $this->get($endpoint);
        });
    }

    /** @param callable(array): array|null $mapper Transforma cada ítem del results a español (DTO::fromArray()->toArray()) */
    private function fetchPaginado(string $cacheKey, string $endpoint, string $resultKey, ?callable $mapper = null): array
    {
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($cacheKey, $endpoint, $resultKey, $mapper) {
            Log::info('SWAPI: cache miss — petición a red', ['key' => $cacheKey, 'endpoint' => $endpoint]);
            $raw     = $this->get($endpoint);
            $results = $raw['results'] ?? [];
            if ($mapper !== null) {
                $results = array_map($mapper, $results);
            }

            return [
                'total'     => $raw['count'] ?? 0,
                'siguiente' => $raw['next'] ?? null,
                'anterior'  => $raw['previous'] ?? null,
                $resultKey  => $results,
            ];
        });
    }

    private function get(string $endpoint): array
    {
        try {
            $response = Http::timeout(self::TIMEOUT)
                ->retry(self::RETRY_TIMES, self::RETRY_SLEEP, function (\Exception $e) use ($endpoint): bool {
                    if ($e instanceof RequestException && $e->response->notFound()) {
                        return false; // No reintentar 404
                    }

                    Log::warning('SWAPI: reintentando petición', [
                        'endpoint' => $endpoint,
                        'error'    => $e->getMessage(),
                    ]);

                    return $e instanceof ConnectionException
                        || ($e instanceof RequestException && $e->response->status() >= 500);
                }, throw: false)
                ->get(self::BASE_URL . $endpoint);

            if ($response->notFound()) {
                Log::error('SWAPI: recurso no encontrado', ['endpoint' => $endpoint]);
                abort(404, 'Recurso no encontrado en SWAPI.');
            }

            if ($response->failed()) {
                Log::error('SWAPI: respuesta fallida', [
                    'endpoint' => $endpoint,
                    'status'   => $response->status(),
                ]);
                abort(502, 'Error al obtener datos de SWAPI.');
            }

            return $response->json();
        } catch (ConnectionException $e) {
            Log::error('SWAPI: timeout o conexión fallida', [
                'endpoint' => $endpoint,
                'error'    => $e->getMessage(),
            ]);
            abort(504, 'SWAPI no responde. Intenta más tarde.');
        }
    }
}
