<?php

declare(strict_types=1);

namespace App\DTOs;

final class PeliculaDTO
{
    public function __construct(
        public readonly string $titulo,
        public readonly int    $numero_episodio,
        public readonly string $texto_apertura,
        public readonly string $director,
        public readonly string $productor,
        public readonly string $fecha_estreno,
        public readonly array  $personajes,
        public readonly array  $planetas,
        public readonly array  $naves,
        public readonly array  $vehiculos,
        public readonly array  $especies,
        public readonly string $creado,
        public readonly string $editado,
        public readonly string $url,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            titulo:          $data['title'] ?? '',
            numero_episodio: (int) ($data['episode_id'] ?? 0),
            texto_apertura:  $data['opening_crawl'] ?? '',
            director:        $data['director'] ?? '',
            productor:       $data['producer'] ?? '',
            fecha_estreno:   $data['release_date'] ?? '',
            personajes:      $data['characters'] ?? [],
            planetas:        $data['planets'] ?? [],
            naves:           $data['starships'] ?? [],
            vehiculos:       $data['vehicles'] ?? [],
            especies:        $data['species'] ?? [],
            creado:          $data['created'] ?? '',
            editado:         $data['edited'] ?? '',
            url:             $data['url'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'titulo'          => $this->titulo,
            'numero_episodio'  => $this->numero_episodio,
            'texto_apertura'  => $this->texto_apertura,
            'director'        => $this->director,
            'productor'       => $this->productor,
            'fecha_estreno'   => $this->fecha_estreno,
            'personajes'      => $this->personajes,
            'planetas'        => $this->planetas,
            'naves'           => $this->naves,
            'vehiculos'       => $this->vehiculos,
            'especies'        => $this->especies,
            'creado'          => $this->creado,
            'editado'         => $this->editado,
            'url'             => $this->url,
        ];
    }
}
