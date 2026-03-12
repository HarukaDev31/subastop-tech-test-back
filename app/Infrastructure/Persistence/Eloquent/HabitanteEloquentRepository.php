<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Local\Habitante\HabitanteRepository;
use App\Models\Habitante;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class HabitanteEloquentRepository implements HabitanteRepository
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = Habitante::with('planeta', 'especie', 'naves');

        if (!empty($filters['nombre'])) {
            $query->nombre((string) $filters['nombre']);
        }

        if (!empty($filters['genero'])) {
            $query->genero((string) $filters['genero']);
        }

        $perPage = isset($filters['per_page']) ? min(max((int) $filters['per_page'], 1), 50) : 10;
        $page    = isset($filters['page']) ? max(1, (int) $filters['page']) : null;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): Habitante
    {
        return Habitante::with('planeta', 'especie', 'naves')->findOrFail($id);
    }

    public function create(array $data): Habitante
    {
        /** @var Habitante $habitante */
        $habitante = Habitante::create($data);
        Log::info('Habitante creado', ['id' => $habitante->id, 'nombre' => $habitante->nombre]);

        return $habitante->load('planeta', 'especie', 'naves');
    }

    public function update(int $id, array $data): Habitante
    {
        $habitante = Habitante::findOrFail($id);
        $habitante->update($data);

        Log::info('Habitante actualizado', ['id' => $habitante->id]);

        return $habitante->load('planeta', 'especie', 'naves');
    }

    public function delete(int $id): void
    {
        $habitante = Habitante::findOrFail($id);
        $habitante->delete();

        Log::info('Habitante eliminado', ['id' => $id]);
    }
}

