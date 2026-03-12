<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\DTOs\EspecieDTO;
use App\DTOs\NaveDTO;
use App\DTOs\PeliculaDTO;
use App\DTOs\PersonajeDTO;
use App\DTOs\PlanetaSwapiDTO;
use App\DTOs\VehiculoDTO;
use PHPUnit\Framework\TestCase;

class DtoTest extends TestCase
{
    public function test_personaje_dto_mapea_correctamente(): void
    {
        $data = [
            'name' => 'Luke Skywalker', 'height' => '172', 'mass' => '77',
            'hair_color' => 'blond', 'skin_color' => 'fair', 'eye_color' => 'blue',
            'birth_year' => '19BBY', 'gender' => 'male',
            'homeworld' => 'url', 'films' => [], 'species' => [],
            'vehicles' => [], 'starships' => [],
            'created' => '2014-12-09', 'edited' => '2014-12-20', 'url' => 'url',
        ];

        $dto = PersonajeDTO::fromArray($data);

        $this->assertSame('Luke Skywalker', $dto->nombre);
        $this->assertSame('172', $dto->altura);
        $this->assertSame('blond', $dto->colorCabello);
        $this->assertSame('blue', $dto->colorOjos);
        $this->assertSame('19BBY', $dto->anioNacimiento);
    }

    public function test_pelicula_dto_mapea_correctamente(): void
    {
        $data = [
            'title' => 'A New Hope', 'episode_id' => 4,
            'opening_crawl' => 'It is...', 'director' => 'George Lucas',
            'producer' => 'Gary Kurtz', 'release_date' => '1977-05-25',
            'characters' => [], 'planets' => [], 'starships' => [],
            'vehicles' => [], 'species' => [],
            'created' => '2014-12-10', 'edited' => '2014-12-20', 'url' => 'url',
        ];

        $dto = PeliculaDTO::fromArray($data);

        $this->assertSame('A New Hope', $dto->titulo);
        $this->assertSame(4, $dto->numero_episodio);
        $this->assertSame('George Lucas', $dto->director);
        $this->assertSame('1977-05-25', $dto->fecha_estreno);
    }

    public function test_especie_dto_mapea_correctamente(): void
    {
        $data = [
            'name' => 'Human', 'classification' => 'mammal',
            'designation' => 'sentient', 'average_height' => '180',
            'skin_colors' => 'fair', 'hair_colors' => 'blonde',
            'eye_colors' => 'blue', 'average_lifespan' => '120',
            'homeworld' => 'url', 'language' => 'Galactic Basic',
            'people' => [], 'films' => [],
            'created' => '2014-12-10', 'edited' => '2014-12-20', 'url' => 'url',
        ];

        $dto = EspecieDTO::fromArray($data);

        $this->assertSame('Human', $dto->nombre);
        $this->assertSame('mammal', $dto->clasificacion);
        $this->assertSame('Galactic Basic', $dto->idioma);
    }

    public function test_vehiculo_dto_mapea_correctamente(): void
    {
        $data = [
            'name' => 'Sand Crawler', 'model' => 'Digger Crawler',
            'manufacturer' => 'Corellia Mining', 'cost_in_credits' => '150000',
            'length' => '36.8', 'max_atmosphering_speed' => '30',
            'crew' => '46', 'passengers' => '30', 'cargo_capacity' => '50000',
            'consumables' => '2 months', 'vehicle_class' => 'wheeled',
            'pilots' => [], 'films' => [],
            'created' => '2014-12-10', 'edited' => '2014-12-20', 'url' => 'url',
        ];

        $dto = VehiculoDTO::fromArray($data);

        $this->assertSame('Sand Crawler', $dto->nombre);
        $this->assertSame('wheeled', $dto->clase_vehiculo);
        $this->assertSame('Corellia Mining', $dto->fabricante);
    }

    public function test_nave_dto_mapea_correctamente(): void
    {
        $data = [
            'name' => 'Death Star', 'model' => 'DS-1 Orbital Battle Station',
            'manufacturer' => 'Imperial Dept', 'cost_in_credits' => '1000000000000',
            'length' => '120000', 'max_atmosphering_speed' => 'n/a',
            'crew' => '342953', 'passengers' => '843342',
            'cargo_capacity' => '1000000000000', 'consumables' => '3 years',
            'hyperdrive_rating' => '4.0', 'MGLT' => '10',
            'starship_class' => 'Deep Space Mobile Battlestation',
            'pilots' => [], 'films' => [],
            'created' => '2014-12-10', 'edited' => '2014-12-20', 'url' => 'url',
        ];

        $dto = NaveDTO::fromArray($data);

        $this->assertSame('Death Star', $dto->nombre);
        $this->assertSame('Deep Space Mobile Battlestation', $dto->claseNave);
    }

    public function test_planeta_swapi_dto_mapea_correctamente(): void
    {
        $data = [
            'name' => 'Tatooine', 'rotation_period' => '23',
            'orbital_period' => '304', 'diameter' => '10465',
            'climate' => 'arid', 'gravity' => '1 standard',
            'terrain' => 'desert', 'surface_water' => '1',
            'population' => '200000', 'residents' => [], 'films' => [],
            'created' => '2014-12-09', 'edited' => '2014-12-20', 'url' => 'url',
        ];

        $dto = PlanetaSwapiDTO::fromArray($data);

        $this->assertSame('Tatooine', $dto->nombre);
        $this->assertSame('arid', $dto->climatico);
        $this->assertSame('desert', $dto->terreno);
    }
}
