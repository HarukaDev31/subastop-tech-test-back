<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\DTOs\PersonajeDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonajeResource extends JsonResource
{
    /** @param PersonajeDTO $resource */
    public function toArray(Request $request): array
    {
        /** @var PersonajeDTO $dto */
        $dto = $this->resource;

        return $dto->toArray();
    }
}
