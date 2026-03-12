<?php

declare(strict_types=1);

namespace App\Application\Local\Nave\UseCases;

use App\Domain\Local\Nave\NaveRepository;

class DeleteNaveUseCase
{
    public function __construct(
        private readonly NaveRepository $naves,
    ) {}

    public function execute(int $id): void
    {
        $this->naves->delete($id);
    }
}

