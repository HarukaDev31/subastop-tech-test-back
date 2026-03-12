<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EspecieLocalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->nombre,
            'classification'  => $this->clasificacion,
            'designation'      => $this->designacion,
            'average_height'   => $this->altura_promedio,
            'skin_colors'      => $this->colores_piel,
            'hair_colors'      => $this->colores_cabello,
            'eye_colors'       => $this->colores_ojos,
            'average_lifespan' => $this->esperanza_vida,
            'language'         => $this->idioma,
            'homeworld'        => new PlanetaResource($this->whenLoaded('planetaNatal')),
            'people'           => HabitanteResource::collection($this->whenLoaded('habitantes')),
            'films'            => PeliculaLocalResource::collection($this->whenLoaded('peliculas')),
            'created_at'       => $this->created_at?->toIso8601String(),
            'updated_at'       => $this->updated_at?->toIso8601String(),
        ];
    }
}
