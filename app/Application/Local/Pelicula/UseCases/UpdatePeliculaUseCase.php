<?php

declare(strict_types=1);

namespace App\Application\Local\Pelicula\UseCases;

use App\Domain\Local\Pelicula\PeliculaRepository;
use App\Models\Pelicula;

class UpdatePeliculaUseCase
{
    public function __construct(
        private readonly PeliculaRepository $peliculas,
    ) {}

    /** @param array<string,mixed> $data */
    public function execute(int $id, array $data): Pelicula
    {
        return $this->peliculas->update($id, $data);
    }
}

