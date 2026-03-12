<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeliculaLocalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->titulo,
            'episode_id'    => $this->episodio_id,
            'opening_crawl' => $this->texto_apertura,
            'director'      => $this->director,
            'producer'      => $this->productor,
            'release_date'  => $this->fecha_estreno?->toDateString(),
            'planets'       => PlanetaResource::collection($this->whenLoaded('planetas')),
            'starships'     => NaveLocalResource::collection($this->whenLoaded('naves')),
            'vehicles'      => VehiculoLocalResource::collection($this->whenLoaded('vehiculos')),
            'species'       => EspecieLocalResource::collection($this->whenLoaded('especies')),
            'created_at'    => $this->created_at?->toIso8601String(),
            'updated_at'    => $this->updated_at?->toIso8601String(),
        ];
    }
}
