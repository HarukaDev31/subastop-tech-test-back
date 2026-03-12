<?php

declare(strict_types=1);

namespace App\Application\Local\Vehiculo\UseCases;

use App\Domain\Local\Vehiculo\VehiculoRepository;
use App\Models\Vehiculo;

class CreateVehiculoUseCase
{
    public function __construct(
        private readonly VehiculoRepository $vehiculos,
    ) {}

    /** @param array<string,mixed> $data */
    public function execute(array $data): Vehiculo
    {
        return $this->vehiculos->create($data);
    }
}

