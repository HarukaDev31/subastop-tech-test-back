<?php

declare(strict_types=1);

namespace App\Application\Local\Planeta\UseCases;

use App\Domain\Local\Planeta\PlanetaRepository;
use App\Models\Planeta;

class CreatePlanetaUseCase
{
    public function __construct(
        private readonly PlanetaRepository $planetas,
    ) {}

    /** @param array<string,mixed> $data */
    public function execute(array $data): Planeta
    {
        return $this->planetas->create($data);
    }
}

