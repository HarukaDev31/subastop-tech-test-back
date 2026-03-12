<?php

declare(strict_types=1);

namespace App\DTOs;

final class VehiculoDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $modelo,
        public readonly string $fabricante,
        public readonly string $costo_creditos,
        public readonly string $longitud,
        public readonly string $velocidad_maxima,
        public readonly string $tripulacion,
        public readonly string $pasajeros,
        public readonly string $capacidad_carga,
        public readonly string $consumibles,
        public readonly string $clase_vehiculo,
        public readonly array  $pilotos,
        public readonly array  $peliculas,
        public readonly string $creado,
        public readonly string $editado,
        public readonly string $url,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre:            $data['name'] ?? '',
            modelo:            $data['model'] ?? '',
            fabricante:        $data['manufacturer'] ?? '',
            costo_creditos:    $data['cost_in_credits'] ?? '',
            longitud:          $data['length'] ?? '',
            velocidad_maxima:  $data['max_atmosphering_speed'] ?? '',
            tripulacion:       $data['crew'] ?? '',
            pasajeros:         $data['passengers'] ?? '',
            capacidad_carga:   $data['cargo_capacity'] ?? '',
            consumibles:       $data['consumables'] ?? '',
            clase_vehiculo:    $data['vehicle_class'] ?? '',
            pilotos:           $data['pilots'] ?? [],
            peliculas:         $data['films'] ?? [],
            creado:            $data['created'] ?? '',
            editado:           $data['edited'] ?? '',
            url:               $data['url'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'nombre'           => $this->nombre,
            'modelo'           => $this->modelo,
            'fabricante'       => $this->fabricante,
            'costo_creditos'   => $this->costo_creditos,
            'longitud'         => $this->longitud,
            'velocidad_maxima' => $this->velocidad_maxima,
            'tripulacion'      => $this->tripulacion,
            'pasajeros'        => $this->pasajeros,
            'capacidad_carga'  => $this->capacidad_carga,
            'consumibles'      => $this->consumibles,
            'clase_vehiculo'   => $this->clase_vehiculo,
            'pilotos'          => $this->pilotos,
            'peliculas'        => $this->peliculas,
            'creado'           => $this->creado,
            'editado'          => $this->editado,
            'url'              => $this->url,
        ];
    }
}
