<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Local\Vehiculo\VehiculoRepository;
use App\Models\Vehiculo;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehiculoEloquentRepository implements VehiculoRepository
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = Vehiculo::with('peliculas');

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

    public function findOrFail(int $id): Vehiculo
    {
        return Vehiculo::with('peliculas')->findOrFail($id);
    }

    public function create(array $data): Vehiculo
    {
        return DB::transaction(function () use ($data): Vehiculo {
            /** @var Vehiculo $vehiculo */
            $vehiculo = Vehiculo::create([
                'nombre'         => $data['nombre'],
                'modelo'         => $data['modelo'] ?? null,
                'fabricante'     => $data['fabricante'] ?? null,
                'clase_vehiculo' => $data['clase_vehiculo'] ?? null,
                'longitud'       => $data['longitud'] ?? null,
            ]);

            if (!empty($data['pelicula_ids'])) {
                $vehiculo->peliculas()->sync($data['pelicula_ids']);
            }

            Log::info('Vehiculo creado', ['id' => $vehiculo->id, 'nombre' => $vehiculo->nombre]);

            return $vehiculo->load('peliculas');
        });
    }

    public function update(int $id, array $data): Vehiculo
    {
        return DB::transaction(function () use ($id, $data): Vehiculo {
            $vehiculo = Vehiculo::findOrFail($id);

            $vehiculo->update(array_filter([
                'nombre'         => $data['nombre'] ?? null,
                'modelo'         => $data['modelo'] ?? null,
                'fabricante'     => $data['fabricante'] ?? null,
                'clase_vehiculo' => $data['clase_vehiculo'] ?? null,
                'longitud'       => $data['longitud'] ?? null,
            ], static fn ($v) => !is_null($v)));

            if (array_key_exists('pelicula_ids', $data)) {
                $vehiculo->peliculas()->sync($data['pelicula_ids'] ?? []);
            }

            Log::info('Vehiculo actualizado', ['id' => $vehiculo->id]);

            return $vehiculo->load('peliculas');
        });
    }

    public function delete(int $id): void
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->delete();

        Log::info('Vehiculo eliminado', ['id' => $id]);
    }
}

