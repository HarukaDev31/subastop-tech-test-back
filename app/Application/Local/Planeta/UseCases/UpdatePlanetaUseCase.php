<?php

declare(strict_types=1);

namespace App\Application\Local\Planeta\UseCases;

use App\Domain\Local\Planeta\PlanetaRepository;
use App\Models\Planeta;

class UpdatePlanetaUseCase
{
    public function __construct(
        private readonly PlanetaRepository $planetas,
    ) {}

    /** @param array<string,mixed> $data */
    public function execute(int $id, array $data): Planeta
    {
        return $this->planetas->update($id, $data);
    }
}

