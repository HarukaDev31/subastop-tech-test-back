<?php

declare(strict_types=1);

namespace App\Application\Local\Vehiculo\UseCases;

use App\Domain\Local\Vehiculo\VehiculoRepository;
use App\Models\Vehiculo;

class UpdateVehiculoUseCase
{
    public function __construct(
        private readonly VehiculoRepository $vehiculos,
    ) {}

    /** @param array<string,mixed> $data */
    public function execute(int $id, array $data): Vehiculo
    {
        return $this->vehiculos->update($id, $data);
    }
}

