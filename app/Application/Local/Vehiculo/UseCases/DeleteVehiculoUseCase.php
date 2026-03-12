<?php

declare(strict_types=1);

namespace App\Application\Local\Vehiculo\UseCases;

use App\Domain\Local\Vehiculo\VehiculoRepository;

class DeleteVehiculoUseCase
{
    public function __construct(
        private readonly VehiculoRepository $vehiculos,
    ) {}

    public function execute(int $id): void
    {
        $this->vehiculos->delete($id);
    }
}

