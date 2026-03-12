<?php

declare(strict_types=1);

namespace App\Domain\Local\Habitante;

use App\Models\Habitante;
use Illuminate\Pagination\LengthAwarePaginator;

interface HabitanteRepository
{
    /** @param array<string,mixed> $filters */
    public function paginate(array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Habitante;

    /** @param array<string,mixed> $data */
    public function create(array $data): Habitante;

    /** @param array<string,mixed> $data */
    public function update(int $id, array $data): Habitante;

    public function delete(int $id): void;
}

