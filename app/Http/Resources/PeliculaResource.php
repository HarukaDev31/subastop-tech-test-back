<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\DTOs\PeliculaDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeliculaResource extends JsonResource
{
    /** @param PeliculaDTO $resource */
    public function toArray(Request $request): array
    {
        /** @var PeliculaDTO $dto */
        $dto = $this->resource;

        return $dto->toArray();
    }
}
