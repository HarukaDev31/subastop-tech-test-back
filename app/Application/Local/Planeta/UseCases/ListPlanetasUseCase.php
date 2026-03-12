<?php

declare(strict_types=1);

namespace App\Application\Local\Planeta\UseCases;

use App\Domain\Local\Planeta\PlanetaRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListPlanetasUseCase
{
    public function __construct(
        private readonly PlanetaRepository $planetas,
    ) {}

    /** @param array<string,mixed> $filters */
    public function execute(array $filters): LengthAwarePaginator
    {
        return $this->planetas->paginate($filters);
    }
}

