<?php

declare(strict_types=1);

namespace App\Application\Local\Especie\UseCases;

use App\Domain\Local\Especie\EspecieRepository;
use App\Models\Especie;

class CreateEspecieUseCase
{
    public function __construct(
        private readonly EspecieRepository $especies,
    ) {}

    /** @param array<string,mixed> $data */
    public function execute(array $data): Especie
    {
        return $this->especies->create($data);
    }
}

