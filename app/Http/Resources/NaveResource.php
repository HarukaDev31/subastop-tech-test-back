<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\DTOs\NaveDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NaveResource extends JsonResource
{
    /** @param NaveDTO $resource */
    public function toArray(Request $request): array
    {
        /** @var NaveDTO $dto */
        $dto = $this->resource;

        return $dto->toArray();
    }
}
