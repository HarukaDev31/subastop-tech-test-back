<?php

declare(strict_types=1);

namespace App\Application\Local\Vehiculo\UseCases;

use App\Domain\Local\Vehiculo\VehiculoRepository;
use App\Models\Vehiculo;

class GetVehiculoUseCase
{
    public function __construct(
        private readonly VehiculoRepository $vehiculos,
    ) {}

    public function execute(int $id): Vehiculo
    {
        return $this->vehiculos->findOrFail($id);
    }
}

