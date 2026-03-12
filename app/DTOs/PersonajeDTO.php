<?php

declare(strict_types=1);

namespace App\DTOs;

final class PersonajeDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $altura,
        public readonly string $masa,
        public readonly string $colorCabello,
        public readonly string $colorPiel,
        public readonly string $colorOjos,
        public readonly string $anioNacimiento,
        public readonly string $genero,
        public readonly string $mundoNatal,
        public readonly array $peliculas,
        public readonly array $especies,
        public readonly array $vehiculos,
        public readonly array $navesEstelares,
        public readonly string $creado,
        public readonly string $editado,
        public readonly string $url,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre:          $data['name'] ?? '',
            altura:          $data['height'] ?? '',
            masa:            $data['mass'] ?? '',
            colorCabello:    $data['hair_color'] ?? '',
            colorPiel:       $data['skin_color'] ?? '',
            colorOjos:       $data['eye_color'] ?? '',
            anioNacimiento:  $data['birth_year'] ?? '',
            genero:          $data['gender'] ?? '',
            mundoNatal:      $data['homeworld'] ?? '',
            peliculas:       $data['films'] ?? [],
            especies:        $data['species'] ?? [],
            vehiculos:       $data['vehicles'] ?? [],
            navesEstelares:  $data['starships'] ?? [],
            creado:          $data['created'] ?? '',
            editado:         $data['edited'] ?? '',
            url:             $data['url'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'nombre'           => $this->nombre,
            'altura'           => $this->altura,
            'peso'             => $this->masa,
            'color_cabello'    => $this->colorCabello,
            'color_piel'       => $this->colorPiel,
            'color_ojos'       => $this->colorOjos,
            'anio_nacimiento'  => $this->anioNacimiento,
            'genero'           => $this->genero,
            'mundo_natal'      => $this->mundoNatal,
            'peliculas'        => $this->peliculas,
            'especies'         => $this->especies,
            'vehiculos'        => $this->vehiculos,
            'naves'            => $this->navesEstelares,
            'creado'           => $this->creado,
            'editado'          => $this->editado,
            'url'              => $this->url,
        ];
    }
}
