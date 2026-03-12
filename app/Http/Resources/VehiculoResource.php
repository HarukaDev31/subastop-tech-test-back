<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\DTOs\VehiculoDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehiculoResource extends JsonResource
{
    /** @param VehiculoDTO $resource */
    public function toArray(Request $request): array
    {
        /** @var VehiculoDTO $dto */
        $dto = $this->resource;

        return $dto->toArray();
    }
}
