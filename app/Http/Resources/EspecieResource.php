<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\DTOs\EspecieDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EspecieResource extends JsonResource
{
    /** @param EspecieDTO $resource */
    public function toArray(Request $request): array
    {
        /** @var EspecieDTO $dto */
        $dto = $this->resource;

        return $dto->toArray();
    }
}
