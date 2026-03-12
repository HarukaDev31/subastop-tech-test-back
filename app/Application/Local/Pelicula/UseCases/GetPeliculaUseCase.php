<?php

declare(strict_types=1);

namespace App\Application\Local\Pelicula\UseCases;

use App\Domain\Local\Pelicula\PeliculaRepository;
use App\Models\Pelicula;

class GetPeliculaUseCase
{
    public function __construct(
        private readonly PeliculaRepository $peliculas,
    ) {}

    public function execute(int $id): Pelicula
    {
        return $this->peliculas->findOrFail($id);
    }
}

