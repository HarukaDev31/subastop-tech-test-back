<?php

declare(strict_types=1);

namespace App\Domain\Local\Vehiculo;

use App\Models\Vehiculo;
use Illuminate\Pagination\LengthAwarePaginator;

interface VehiculoRepository
{
    /** @param array<string,mixed> $filters */
    public function paginate(array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Vehiculo;

    /** @param array<string,mixed> $data */
    public function create(array $data): Vehiculo;

    /** @param array<string,mixed> $data */
    public function update(int $id, array $data): Vehiculo;

    public function delete(int $id): void;
}

