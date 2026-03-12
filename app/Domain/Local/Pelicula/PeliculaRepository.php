<?php

declare(strict_types=1);

namespace App\Domain\Local\Pelicula;

use App\Models\Pelicula;
use Illuminate\Pagination\LengthAwarePaginator;

interface PeliculaRepository
{
    /** @param array<string,mixed> $filters */
    public function paginate(array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Pelicula;

    /** @param array<string,mixed> $data */
    public function create(array $data): Pelicula;

    /** @param array<string,mixed> $data */
    public function update(int $id, array $data): Pelicula;

    public function delete(int $id): void;
}

