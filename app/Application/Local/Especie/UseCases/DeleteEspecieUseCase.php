<?php

declare(strict_types=1);

namespace App\Application\Local\Especie\UseCases;

use App\Domain\Local\Especie\EspecieRepository;

class DeleteEspecieUseCase
{
    public function __construct(
        private readonly EspecieRepository $especies,
    ) {}

    public function execute(int $id): void
    {
        $this->especies->delete($id);
    }
}

