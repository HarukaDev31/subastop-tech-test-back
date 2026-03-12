<?php

declare(strict_types=1);

namespace App\DTOs;

final class EspecieDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $clasificacion,
        public readonly string $designacion,
        public readonly string $altura_promedio,
        public readonly string $colores_piel,
        public readonly string $colores_cabello,
        public readonly string $colores_ojos,
        public readonly string $esperanza_vida,
        public readonly string $mundo_natal,
        public readonly string $idioma,
        public readonly array  $personas,
        public readonly array  $peliculas,
        public readonly string $creado,
        public readonly string $editado,
        public readonly string $url,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre:          $data['name'] ?? '',
            clasificacion:   $data['classification'] ?? '',
            designacion:     $data['designation'] ?? '',
            altura_promedio: $data['average_height'] ?? '',
            colores_piel:    $data['skin_colors'] ?? '',
            colores_cabello: $data['hair_colors'] ?? '',
            colores_ojos:    $data['eye_colors'] ?? '',
            esperanza_vida:  $data['average_lifespan'] ?? '',
            mundo_natal:     $data['homeworld'] ?? '',
            idioma:          $data['language'] ?? '',
            personas:        $data['people'] ?? [],
            peliculas:       $data['films'] ?? [],
            creado:          $data['created'] ?? '',
            editado:         $data['edited'] ?? '',
            url:             $data['url'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'nombre'           => $this->nombre,
            'clasificacion'    => $this->clasificacion,
            'designacion'      => $this->designacion,
            'altura_promedio'  => $this->altura_promedio,
            'colores_piel'     => $this->colores_piel,
            'colores_cabello'  => $this->colores_cabello,
            'colores_ojos'     => $this->colores_ojos,
            'esperanza_vida'   => $this->esperanza_vida,
            'mundo_natal'      => $this->mundo_natal,
            'idioma'           => $this->idioma,
            'personas'         => $this->personas,
            'peliculas'        => $this->peliculas,
            'creado'           => $this->creado,
            'editado'          => $this->editado,
            'url'              => $this->url,
        ];
    }
}
