<?php

declare(strict_types=1);

namespace App\Application\Local\Vehiculo\UseCases;

use App\Domain\Local\Vehiculo\VehiculoRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListVehiculosUseCase
{
    public function __construct(
        private readonly VehiculoRepository $vehiculos,
    ) {}

    /** @param array<string,mixed> $filters */
    public function execute(array $filters): LengthAwarePaginator
    {
        return $this->vehiculos->paginate($filters);
    }
}

