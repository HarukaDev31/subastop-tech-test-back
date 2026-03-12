<?php

declare(strict_types=1);

namespace App\Domain\Local\Planeta;

use App\Models\Planeta;
use Illuminate\Pagination\LengthAwarePaginator;

interface PlanetaRepository
{
    /** @param array<string,mixed> $filters */
    public function paginate(array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Planeta;

    /** @param array<string,mixed> $data */
    public function create(array $data): Planeta;

    /** @param array<string,mixed> $data */
    public function update(int $id, array $data): Planeta;

    public function delete(int $id): void;
}

