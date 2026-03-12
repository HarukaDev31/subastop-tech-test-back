<?php

declare(strict_types=1);

namespace App\Application\Local\Pelicula\UseCases;

use App\Domain\Local\Pelicula\PeliculaRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListPeliculasUseCase
{
    public function __construct(
        private readonly PeliculaRepository $peliculas,
    ) {}

    /** @param array<string,mixed> $filters */
    public function execute(array $filters): LengthAwarePaginator
    {
        return $this->peliculas->paginate($filters);
    }
}

