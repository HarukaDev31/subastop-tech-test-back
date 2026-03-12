<?php

declare(strict_types=1);

namespace App\Application\Local\Pelicula\UseCases;

use App\Domain\Local\Pelicula\PeliculaRepository;

class DeletePeliculaUseCase
{
    public function __construct(
        private readonly PeliculaRepository $peliculas,
    ) {}

    public function execute(int $id): void
    {
        $this->peliculas->delete($id);
    }
}

