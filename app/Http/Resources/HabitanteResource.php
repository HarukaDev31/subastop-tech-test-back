<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HabitanteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->nombre,
            'planet_id'     => $this->planeta_id,
            'species_id'    => $this->especie_id,
            'height'        => $this->altura,
            'mass'          => $this->masa,
            'hair_color'    => $this->color_cabello,
            'skin_color'    => $this->color_piel,
            'eye_color'     => $this->color_ojos,
            'birth_year'    => $this->anio_nacimiento,
            'gender'        => $this->genero,
            'planet'        => new PlanetaResource($this->whenLoaded('planeta')),
            'species'       => new EspecieLocalResource($this->whenLoaded('especie')),
            'starships'     => NaveLocalResource::collection($this->whenLoaded('naves')),
            'created_at'    => $this->created_at?->toIso8601String(),
            'updated_at'    => $this->updated_at?->toIso8601String(),
        ];
    }
}
