<?php

declare(strict_types=1);

namespace App\Application\Local\Especie\UseCases;

use App\Domain\Local\Especie\EspecieRepository;
use App\Models\Especie;

class GetEspecieUseCase
{
    public function __construct(
        private readonly EspecieRepository $especies,
    ) {}

    public function execute(int $id): Especie
    {
        return $this->especies->findOrFail($id);
    }
}

