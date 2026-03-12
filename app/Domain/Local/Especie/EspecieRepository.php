<?php

declare(strict_types=1);

namespace App\Domain\Local\Especie;

use App\Models\Especie;
use Illuminate\Pagination\LengthAwarePaginator;

interface EspecieRepository
{
    /** @param array<string,mixed> $filters */
    public function paginate(array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Especie;

    /** @param array<string,mixed> $data */
    public function create(array $data): Especie;

    /** @param array<string,mixed> $data */
    public function update(int $id, array $data): Especie;

    public function delete(int $id): void;
}

