<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NaveLocalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                       => $this->id,
            'name'                     => $this->nombre,
            'model'                    => $this->modelo,
            'manufacturer'             => $this->fabricante,
            'cost_in_credits'          => $this->costo_creditos,
            'length'                   => $this->longitud,
            'max_atmosphering_speed'   => $this->velocidad_maxima_atmosfera,
            'crew'                     => $this->tripulacion,
            'passengers'               => $this->pasajeros,
            'cargo_capacity'           => $this->capacidad_carga,
            'consumables'              => $this->consumibles,
            'hyperdrive_rating'        => $this->clasificacion_hiperimpulsor,
            'mglt'                     => $this->mglt,
            'starship_class'           => $this->clase_nave,
            'films'                    => PeliculaLocalResource::collection($this->whenLoaded('peliculas')),
            'pilots'                   => HabitanteResource::collection($this->whenLoaded('pilotos')),
            'created_at'               => $this->created_at?->toIso8601String(),
            'updated_at'               => $this->updated_at?->toIso8601String(),
        ];
    }
}
