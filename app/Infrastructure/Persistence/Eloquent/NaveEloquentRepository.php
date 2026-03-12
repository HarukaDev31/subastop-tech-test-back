<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Local\Nave\NaveRepository;
use App\Models\Nave;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NaveEloquentRepository implements NaveRepository
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = Nave::with(['peliculas', 'pilotos']);

        if (!empty($filters['nombre'])) {
            $query->nombre((string) $filters['nombre']);
        }

        if (!empty($filters['clase'])) {
            $query->clase((string) $filters['clase']);
        }

        if (!empty($filters['recientes'])) {
            $query->recientes((int) $filters['recientes']);
        }

        $perPage = isset($filters['per_page']) ? min(max((int) $filters['per_page'], 1), 50) : 10;
        $page    = isset($filters['page']) ? max(1, (int) $filters['page']) : null;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): Nave
    {
        return Nave::with(['peliculas', 'pilotos'])->findOrFail($id);
    }

    public function create(array $data): Nave
    {
        return DB::transaction(function () use ($data): Nave {
            /** @var Nave $nave */
            $nave = Nave::create([
                'nombre'          => $data['nombre'],
                'modelo'          => $data['modelo'] ?? null,
                'fabricante'      => $data['fabricante'] ?? null,
                'clase_nave'      => $data['clase_nave'] ?? null,
                'longitud'        => $data['longitud'] ?? null,
                'capacidad_carga' => $data['capacidad_carga'] ?? null,
            ]);

            if (!empty($data['piloto_ids'])) {
                $nave->pilotos()->sync($data['piloto_ids']);
            }

            if (!empty($data['pelicula_ids'])) {
                $nave->peliculas()->sync($data['pelicula_ids']);
            }

            Log::info('Nave creada', ['id' => $nave->id, 'nombre' => $nave->nombre]);

            return $nave->load(['peliculas', 'pilotos']);
        });
    }

    public function update(int $id, array $data): Nave
    {
        return DB::transaction(function () use ($id, $data): Nave {
            $nave = Nave::findOrFail($id);

            $nave->update(array_filter([
                'nombre'          => $data['nombre'] ?? null,
                'modelo'          => $data['modelo'] ?? null,
                'fabricante'      => $data['fabricante'] ?? null,
                'clase_nave'      => $data['clase_nave'] ?? null,
                'longitud'        => $data['longitud'] ?? null,
                'capacidad_carga' => $data['capacidad_carga'] ?? null,
            ], static fn ($v) => !is_null($v)));

            if (array_key_exists('piloto_ids', $data)) {
                $nave->pilotos()->sync($data['piloto_ids'] ?? []);
            }

            if (array_key_exists('pelicula_ids', $data)) {
                $nave->peliculas()->sync($data['pelicula_ids'] ?? []);
            }

            Log::info('Nave actualizada', ['id' => $nave->id]);

            return $nave->load(['peliculas', 'pilotos']);
        });
    }

    public function delete(int $id): void
    {
        $nave = Nave::findOrFail($id);
        $nave->delete();

        Log::info('Nave eliminada', ['id' => $id]);
    }
}

