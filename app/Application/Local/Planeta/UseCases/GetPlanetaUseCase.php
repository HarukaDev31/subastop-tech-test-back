<?php

declare(strict_types=1);

namespace App\Application\Local\Planeta\UseCases;

use App\Domain\Local\Planeta\PlanetaRepository;
use App\Models\Planeta;

class GetPlanetaUseCase
{
    public function __construct(
        private readonly PlanetaRepository $planetas,
    ) {}

    public function execute(int $id): Planeta
    {
        return $this->planetas->findOrFail($id);
    }
}

