<?php

declare(strict_types=1);

namespace App\Application\Local\Nave\UseCases;

use App\Domain\Local\Nave\NaveRepository;
use App\Models\Nave;

class GetNaveUseCase
{
    public function __construct(
        private readonly NaveRepository $naves,
    ) {}

    public function execute(int $id): Nave
    {
        return $this->naves->findOrFail($id);
    }
}

