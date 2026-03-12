<?php

declare(strict_types=1);

namespace App\Domain\Local\Nave;

use App\Models\Nave;
use Illuminate\Pagination\LengthAwarePaginator;

interface NaveRepository
{
    /** @param array<string,mixed> $filters */
    public function paginate(array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Nave;

    /** @param array<string,mixed> $data */
    public function create(array $data): Nave;

    /** @param array<string,mixed> $data */
    public function update(int $id, array $data): Nave;

    public function delete(int $id): void;
}

