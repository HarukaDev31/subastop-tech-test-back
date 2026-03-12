<?php

declare(strict_types=1);

namespace App\DTOs;

final class NaveDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $modelo,
        public readonly string $fabricante,
        public readonly string $costoEnCreditos,
        public readonly string $longitud,
        public readonly string $velocidadAtmosferica,
        public readonly string $tripulacion,
        public readonly string $pasajeros,
        public readonly string $capacidadCarga,
        public readonly string $consumibles,
        public readonly string $clasificacionHiperimpulsor,
        public readonly string $ratingMGLT,
        public readonly string $claseNave,
        public readonly array $pilotos,
        public readonly array $peliculas,
        public readonly string $creado,
        public readonly string $editado,
        public readonly string $url,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre:                    $data['name'] ?? '',
            modelo:                    $data['model'] ?? '',
            fabricante:                $data['manufacturer'] ?? '',
            costoEnCreditos:           $data['cost_in_credits'] ?? '',
            longitud:                  $data['length'] ?? '',
            velocidadAtmosferica:      $data['max_atmosphering_speed'] ?? '',
            tripulacion:               $data['crew'] ?? '',
            pasajeros:                 $data['passengers'] ?? '',
            capacidadCarga:            $data['cargo_capacity'] ?? '',
            consumibles:               $data['consumables'] ?? '',
            clasificacionHiperimpulsor: $data['hyperdrive_rating'] ?? '',
            ratingMGLT:                $data['MGLT'] ?? '',
            claseNave:                 $data['starship_class'] ?? '',
            pilotos:                   $data['pilots'] ?? [],
            peliculas:                 $data['films'] ?? [],
            creado:                    $data['created'] ?? '',
            editado:                   $data['edited'] ?? '',
            url:                       $data['url'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'nombre'                         => $this->nombre,
            'modelo'                         => $this->modelo,
            'fabricante'                     => $this->fabricante,
            'costo_creditos'                 => $this->costoEnCreditos,
            'longitud'                       => $this->longitud,
            'velocidad_maxima_atmosfera'     => $this->velocidadAtmosferica,
            'tripulacion'                    => $this->tripulacion,
            'pasajeros'                      => $this->pasajeros,
            'capacidad_carga'                => $this->capacidadCarga,
            'consumibles'                    => $this->consumibles,
            'clasificacion_hiperimpulsor'    => $this->clasificacionHiperimpulsor,
            'MGLT'                           => $this->ratingMGLT,
            'clase_nave'                     => $this->claseNave,
            'pilotos'                        => $this->pilotos,
            'peliculas'                      => $this->peliculas,
            'creado'                         => $this->creado,
            'editado'                        => $this->editado,
            'url'                            => $this->url,
        ];
    }
}
