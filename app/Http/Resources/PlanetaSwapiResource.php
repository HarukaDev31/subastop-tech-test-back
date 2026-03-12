<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\DTOs\PlanetaSwapiDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanetaSwapiResource extends JsonResource
{
    /** @param PlanetaSwapiDTO $resource */
    public function toArray(Request $request): array
    {
        /** @var PlanetaSwapiDTO $dto */
        $dto = $this->resource;

        return $dto->toArray();
    }
}
