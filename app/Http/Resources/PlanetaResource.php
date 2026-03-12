<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanetaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->nombre,
            'climate'          => $this->clima,
            'terrain'          => $this->terreno,
            'diameter'         => $this->diametro,
            'population'       => $this->poblacion,
            'rotation_period'  => $this->periodo_rotacion,
            'orbital_period'   => $this->periodo_orbital,
            'gravity'          => $this->gravedad,
            'surface_water'    => $this->agua_superficial,
            'inhabitants'      => HabitanteResource::collection($this->whenLoaded('habitantes')),
            'species'          => EspecieLocalResource::collection($this->whenLoaded('especies')),
            'films'            => PeliculaLocalResource::collection($this->whenLoaded('peliculas')),
            'created_at'       => $this->created_at?->toIso8601String(),
            'updated_at'       => $this->updated_at?->toIso8601String(),
        ];
    }
}
