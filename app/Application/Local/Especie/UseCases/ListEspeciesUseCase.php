<?php

declare(strict_types=1);

namespace App\Application\Local\Especie\UseCases;

use App\Domain\Local\Especie\EspecieRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListEspeciesUseCase
{
    public function __construct(
        private readonly EspecieRepository $especies,
    ) {}

    /** @param array<string,mixed> $filters */
    public function execute(array $filters): LengthAwarePaginator
    {
        return $this->especies->paginate($filters);
    }
}

