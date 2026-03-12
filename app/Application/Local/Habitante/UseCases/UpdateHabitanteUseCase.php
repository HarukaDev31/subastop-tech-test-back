<?php

declare(strict_types=1);

namespace App\Application\Local\Habitante\UseCases;

use App\Domain\Local\Habitante\HabitanteRepository;
use App\Models\Habitante;

class UpdateHabitanteUseCase
{
    public function __construct(
        private readonly HabitanteRepository $habitantes,
    ) {}

    /** @param array<string,mixed> $data */
    public function execute(int $id, array $data): Habitante
    {
        return $this->habitantes->update($id, $data);
    }
}

