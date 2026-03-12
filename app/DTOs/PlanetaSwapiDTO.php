<?php

declare(strict_types=1);

namespace App\DTOs;

final class PlanetaSwapiDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $periodoRotacion,
        public readonly string $periodoOrbital,
        public readonly string $diametro,
        public readonly string $climatico,
        public readonly string $gravedad,
        public readonly string $terreno,
        public readonly string $aguaSuperficial,
        public readonly string $poblacion,
        public readonly array $residentes,
        public readonly array $peliculas,
        public readonly string $creado,
        public readonly string $editado,
        public readonly string $url,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre:           $data['name'] ?? '',
            periodoRotacion:  $data['rotation_period'] ?? '',
            periodoOrbital:   $data['orbital_period'] ?? '',
            diametro:         $data['diameter'] ?? '',
            climatico:        $data['climate'] ?? '',
            gravedad:         $data['gravity'] ?? '',
            terreno:          $data['terrain'] ?? '',
            aguaSuperficial:  $data['surface_water'] ?? '',
            poblacion:        $data['population'] ?? '',
            residentes:       $data['residents'] ?? [],
            peliculas:        $data['films'] ?? [],
            creado:           $data['created'] ?? '',
            editado:          $data['edited'] ?? '',
            url:              $data['url'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'nombre'           => $this->nombre,
            'periodo_rotacion' => $this->periodoRotacion,
            'periodo_orbital'  => $this->periodoOrbital,
            'diametro'         => $this->diametro,
            'clima'            => $this->climatico,
            'gravedad'         => $this->gravedad,
            'terreno'          => $this->terreno,
            'agua_superficial' => $this->aguaSuperficial,
            'poblacion'        => $this->poblacion,
            'residentes'       => $this->residentes,
            'peliculas'        => $this->peliculas,
            'creado'           => $this->creado,
            'editado'          => $this->editado,
            'url'              => $this->url,
        ];
    }
}
