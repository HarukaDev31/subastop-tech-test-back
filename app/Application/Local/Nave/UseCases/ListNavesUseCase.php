<?php

declare(strict_types=1);

namespace App\Application\Local\Nave\UseCases;

use App\Domain\Local\Nave\NaveRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListNavesUseCase
{
    public function __construct(
        private readonly NaveRepository $naves,
    ) {}

    /** @param array<string,mixed> $filters */
    public function execute(array $filters): LengthAwarePaginator
    {
        return $this->naves->paginate($filters);
    }
}

