<?php

declare(strict_types=1);

namespace App\Application\Local\Habitante\UseCases;

use App\Domain\Local\Habitante\HabitanteRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListHabitantesUseCase
{
    public function __construct(
        private readonly HabitanteRepository $habitantes,
    ) {}

    /** @param array<string,mixed> $filters */
    public function execute(array $filters): LengthAwarePaginator
    {
        return $this->habitantes->paginate($filters);
    }
}

