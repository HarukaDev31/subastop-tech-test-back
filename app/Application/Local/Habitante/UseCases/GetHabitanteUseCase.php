<?php

declare(strict_types=1);

namespace App\Application\Local\Habitante\UseCases;

use App\Domain\Local\Habitante\HabitanteRepository;
use App\Models\Habitante;

class GetHabitanteUseCase
{
    public function __construct(
        private readonly HabitanteRepository $habitantes,
    ) {}

    public function execute(int $id): Habitante
    {
        return $this->habitantes->findOrFail($id);
    }
}

