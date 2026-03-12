<?php

declare(strict_types=1);

namespace App\Application\Local\Nave\UseCases;

use App\Domain\Local\Nave\NaveRepository;
use App\Models\Nave;

class CreateNaveUseCase
{
    public function __construct(
        private readonly NaveRepository $naves,
    ) {}

    /** @param array<string,mixed> $data */
    public function execute(array $data): Nave
    {
        return $this->naves->create($data);
    }
}

