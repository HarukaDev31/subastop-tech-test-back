<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Local\Especie\EspecieRepository;
use App\Models\Especie;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EspecieEloquentRepository implements EspecieRepository
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = Especie::with(['planetaNatal', 'habitantes', 'peliculas']);

        if (!empty($filters['nombre'])) {
            $query->nombre((string) $filters['nombre']);
        }

        if (!empty($filters['clasificacion'])) {
            $query->clasificacion((string) $filters['clasificacion']);
        }

        if (!empty($filters['recientes'])) {
            $query->recientes((int) $filters['recientes']);
        }

        $perPage = isset($filters['per_page']) ? min(max((int) $filters['per_page'], 1), 50) : 10;
        $page    = isset($filters['page']) ? max(1, (int) $filters['page']) : null;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): Especie
    {
        return Especie::with(['planetaNatal', 'habitantes', 'peliculas'])->findOrFail($id);
    }

    public function create(array $data): Especie
    {
        return DB::transaction(function () use ($data): Especie {
            /** @var Especie $especie */
            $especie = Especie::create([
                'nombre'           => $data['nombre'],
                'clasificacion'    => $data['clasificacion'] ?? null,
                'idioma'           => $data['idioma'] ?? null,
                'planeta_natal_id' => $data['planeta_natal_id'] ?? null,
            ]);

            if (!empty($data['pelicula_ids'])) {
                $especie->peliculas()->sync($data['pelicula_ids']);
            }

            Log::info('Especie creada', ['id' => $especie->id, 'nombre' => $especie->nombre]);

            return $especie->load(['planetaNatal', 'habitantes', 'peliculas']);
        });
    }

    public function update(int $id, array $data): Especie
    {
        return DB::transaction(function () use ($id, $data): Especie {
            $especie = Especie::findOrFail($id);

            $especie->update(array_filter([
                'nombre'           => $data['nombre'] ?? null,
                'clasificacion'    => $data['clasificacion'] ?? null,
                'idioma'           => $data['idioma'] ?? null,
                'planeta_natal_id' => $data['planeta_natal_id'] ?? null,
            ], static fn ($v) => !is_null($v)));

            if (array_key_exists('pelicula_ids', $data)) {
                $especie->peliculas()->sync($data['pelicula_ids'] ?? []);
            }

            Log::info('Especie actualizada', ['id' => $especie->id]);

            return $especie->load(['planetaNatal', 'habitantes', 'peliculas']);
        });
    }

    public function delete(int $id): void
    {
        $especie = Especie::findOrFail($id);
        $especie->delete();

        Log::info('Especie eliminada', ['id' => $id]);
    }
}

