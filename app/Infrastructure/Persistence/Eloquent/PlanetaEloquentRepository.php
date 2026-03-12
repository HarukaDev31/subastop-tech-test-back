<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Local\Planeta\PlanetaRepository;
use App\Models\Planeta;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanetaEloquentRepository implements PlanetaRepository
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = Planeta::with('habitantes');

        if (!empty($filters['nombre'])) {
            $query->nombre((string) $filters['nombre']);
        }

        if (!empty($filters['clima'])) {
            $query->clima((string) $filters['clima']);
        }

        if (!empty($filters['terreno'])) {
            $query->terreno((string) $filters['terreno']);
        }

        if (!empty($filters['recientes'])) {
            $query->recientes((int) $filters['recientes']);
        }

        $perPage = isset($filters['per_page']) ? min(max((int) $filters['per_page'], 1), 50) : 10;
        $page    = isset($filters['page']) ? max(1, (int) $filters['page']) : null;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): Planeta
    {
        return Planeta::with('habitantes')->findOrFail($id);
    }

    public function create(array $data): Planeta
    {
        return DB::transaction(function () use ($data): Planeta {
            /** @var Planeta $planeta */
            $planeta = Planeta::create([
                'nombre'           => $data['nombre'],
                'clima'            => $data['clima'] ?? null,
                'terreno'          => $data['terreno'] ?? null,
                'diametro'         => $data['diametro'] ?? null,
                'poblacion'        => $data['poblacion'] ?? null,
                'periodo_rotacion' => $data['periodo_rotacion'] ?? null,
                'periodo_orbital'  => $data['periodo_orbital'] ?? null,
                'gravedad'         => $data['gravedad'] ?? null,
                'agua_superficial' => $data['agua_superficial'] ?? null,
            ]);

            if (!empty($data['habitantes']) && is_array($data['habitantes'])) {
                $habitantes = array_map(
                    fn (array $h): array => [
                        'nombre'          => $h['nombre'],
                        'altura'          => $h['altura'] ?? null,
                        'masa'            => $h['masa'] ?? null,
                        'color_cabello'   => $h['color_cabello'] ?? null,
                        'color_piel'      => $h['color_piel'] ?? null,
                        'color_ojos'      => $h['color_ojos'] ?? null,
                        'anio_nacimiento' => $h['anio_nacimiento'] ?? null,
                        'genero'          => $h['genero'] ?? null,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ],
                    $data['habitantes']
                );

                $planeta->habitantes()->insert(
                    array_map(fn (array $h): array => array_merge($h, ['planeta_id' => $planeta->id]), $habitantes)
                );
            }

            Log::info('Planeta creado', ['id' => $planeta->id, 'nombre' => $planeta->nombre]);

            return $planeta->load('habitantes');
        });
    }

    public function update(int $id, array $data): Planeta
    {
        return DB::transaction(function () use ($id, $data): Planeta {
            $planeta = Planeta::findOrFail($id);

            $planeta->update(array_filter([
                'nombre'           => $data['nombre'] ?? null,
                'clima'            => $data['clima'] ?? null,
                'terreno'          => $data['terreno'] ?? null,
                'diametro'         => $data['diametro'] ?? null,
                'poblacion'        => $data['poblacion'] ?? null,
                'periodo_rotacion' => $data['periodo_rotacion'] ?? null,
                'periodo_orbital'  => $data['periodo_orbital'] ?? null,
                'gravedad'         => $data['gravedad'] ?? null,
                'agua_superficial' => $data['agua_superficial'] ?? null,
            ], static fn ($v) => !is_null($v)));

            Log::info('Planeta actualizado', ['id' => $planeta->id]);

            return $planeta->load('habitantes');
        });
    }

    public function delete(int $id): void
    {
        $planeta = Planeta::findOrFail($id);
        $planeta->delete();

        Log::info('Planeta eliminado', ['id' => $id]);
    }
}

