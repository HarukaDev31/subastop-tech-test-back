<?php

declare(strict_types=1);

namespace App\Application\Local\Planeta\UseCases;

use App\Domain\Local\Planeta\PlanetaRepository;

class DeletePlanetaUseCase
{
    public function __construct(
        private readonly PlanetaRepository $planetas,
    ) {}

    public function execute(int $id): void
    {
        $this->planetas->delete($id);
    }
}

