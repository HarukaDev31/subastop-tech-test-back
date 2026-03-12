<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Local\Pelicula\PeliculaRepository;
use App\Models\Pelicula;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeliculaEloquentRepository implements PeliculaRepository
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = Pelicula::with(['planetas', 'naves', 'vehiculos', 'especies']);

        if (!empty($filters['titulo'])) {
            $query->titulo((string) $filters['titulo']);
        }

        if (!empty($filters['director'])) {
            $query->director((string) $filters['director']);
        }

        if (!empty($filters['recientes'])) {
            $query->recientes((int) $filters['recientes']);
        }

        $perPage = isset($filters['per_page']) ? min(max((int) $filters['per_page'], 1), 50) : 10;
        $page    = isset($filters['page']) ? max(1, (int) $filters['page']) : null;

        return $query->orderBy('episodio_id')->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): Pelicula
    {
        return Pelicula::with(['planetas', 'naves', 'vehiculos', 'especies'])->findOrFail($id);
    }

    public function create(array $data): Pelicula
    {
        return DB::transaction(function () use ($data): Pelicula {
            /** @var Pelicula $pelicula */
            $pelicula = Pelicula::create([
                'titulo'        => $data['titulo'],
                'episodio_id'   => $data['episodio_id'],
                'director'      => $data['director'],
                'productor'     => $data['productor'] ?? null,
                'fecha_estreno' => $data['fecha_estreno'] ?? null,
            ]);

            if (!empty($data['planeta_ids'])) {
                $pelicula->planetas()->sync($data['planeta_ids']);
            }

            if (!empty($data['nave_ids'])) {
                $pelicula->naves()->sync($data['nave_ids']);
            }

            if (!empty($data['vehiculo_ids'])) {
                $pelicula->vehiculos()->sync($data['vehiculo_ids']);
            }

            if (!empty($data['especie_ids'])) {
                $pelicula->especies()->sync($data['especie_ids']);
            }

            Log::info('Pelicula creada', ['id' => $pelicula->id, 'titulo' => $pelicula->titulo]);

            return $pelicula->load(['planetas', 'naves', 'vehiculos', 'especies']);
        });
    }

    public function update(int $id, array $data): Pelicula
    {
        return DB::transaction(function () use ($id, $data): Pelicula {
            $pelicula = Pelicula::findOrFail($id);

            $pelicula->update(array_filter([
                'titulo'        => $data['titulo'] ?? null,
                'episodio_id'   => $data['episodio_id'] ?? null,
                'director'      => $data['director'] ?? null,
                'productor'     => $data['productor'] ?? null,
                'fecha_estreno' => $data['fecha_estreno'] ?? null,
            ], static fn ($v) => !is_null($v)));

            if (array_key_exists('planeta_ids', $data)) {
                $pelicula->planetas()->sync($data['planeta_ids'] ?? []);
            }

            if (array_key_exists('nave_ids', $data)) {
                $pelicula->naves()->sync($data['nave_ids'] ?? []);
            }

            if (array_key_exists('vehiculo_ids', $data)) {
                $pelicula->vehiculos()->sync($data['vehiculo_ids'] ?? []);
            }

            if (array_key_exists('especie_ids', $data)) {
                $pelicula->especies()->sync($data['especie_ids'] ?? []);
            }

            Log::info('Pelicula actualizada', ['id' => $pelicula->id]);

            return $pelicula->load(['planetas', 'naves', 'vehiculos', 'especies']);
        });
    }

    public function delete(int $id): void
    {
        $pelicula = Pelicula::findOrFail($id);
        $pelicula->delete();

        Log::info('Pelicula eliminada', ['id' => $id]);
    }
}

