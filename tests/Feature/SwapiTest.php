<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SwapiTest extends TestCase
{
    private string $apiKey = 'test-api-key-123';

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.api_key' => $this->apiKey]);
    }

    // -------------------------------------------------------------------------
    // Personajes
    // -------------------------------------------------------------------------

    public function test_listar_personajes_retorna_datos_en_espanol(): void
    {
        Http::fake([
            'swapi.py4e.com/api/people/*' => Http::response([
                'count'    => 82,
                'next'     => 'https://swapi.py4e.com/api/people/?page=2',
                'previous' => null,
                'results'  => [
                    [
                        'name'        => 'Luke Skywalker',
                        'height'      => '172',
                        'mass'        => '77',
                        'hair_color'  => 'blond',
                        'skin_color'  => 'fair',
                        'eye_color'   => 'blue',
                        'birth_year'  => '19BBY',
                        'gender'      => 'male',
                        'homeworld'   => 'https://swapi.py4e.com/api/planets/1/',
                        'films'       => [],
                        'species'     => [],
                        'vehicles'    => [],
                        'starships'   => [],
                        'created'     => '2014-12-09T13:50:51.644000Z',
                        'edited'      => '2014-12-20T21:17:56.891000Z',
                        'url'         => 'https://swapi.py4e.com/api/people/1/',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->getJson('/api/swapi/personajes');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'total',
                'siguiente',
                'anterior',
                'datos',
            ])
            ->assertJsonPath('success', true)
            ->assertJsonPath('total', 82);
    }

    public function test_obtener_personaje_retorna_dto_mapeado(): void
    {
        Http::fake([
            'swapi.py4e.com/api/people/1/' => Http::response([
                'name'        => 'Luke Skywalker',
                'height'      => '172',
                'mass'        => '77',
                'hair_color'  => 'blond',
                'skin_color'  => 'fair',
                'eye_color'   => 'blue',
                'birth_year'  => '19BBY',
                'gender'      => 'male',
                'homeworld'   => 'https://swapi.py4e.com/api/planets/1/',
                'films'       => [],
                'species'     => [],
                'vehicles'    => [],
                'starships'   => [],
                'created'     => '2014-12-09T13:50:51.644000Z',
                'edited'      => '2014-12-20T21:17:56.891000Z',
                'url'         => 'https://swapi.py4e.com/api/people/1/',
            ], 200),
        ]);

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->getJson('/api/swapi/personajes/1');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('datos.nombre', 'Luke Skywalker')
            ->assertJsonPath('datos.color_cabello', 'blond')
            ->assertJsonPath('datos.color_ojos', 'blue')
            ->assertJsonPath('datos.anio_nacimiento', '19BBY');

        // Verificar que NO contiene claves en inglés
        $datos = $response->json('datos');
        $this->assertArrayNotHasKey('name', $datos);
        $this->assertArrayNotHasKey('hair_color', $datos);
    }

    public function test_personaje_no_encontrado_retorna_404(): void
    {
        Http::fake([
            'swapi.py4e.com/api/people/9999/' => Http::response([], 404),
        ]);

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->getJson('/api/swapi/personajes/9999');

        $response->assertStatus(404);
    }

    // -------------------------------------------------------------------------
    // Naves
    // -------------------------------------------------------------------------

    public function test_obtener_nave_retorna_dto_mapeado(): void
    {
        Http::fake([
            'swapi.py4e.com/api/starships/9/' => Http::response([
                'name'                    => 'Death Star',
                'model'                   => 'DS-1 Orbital Battle Station',
                'manufacturer'            => 'Imperial Department of Military Research',
                'cost_in_credits'         => '1000000000000',
                'length'                  => '120000',
                'max_atmosphering_speed'  => 'n/a',
                'crew'                    => '342,953',
                'passengers'              => '843,342',
                'cargo_capacity'          => '1000000000000',
                'consumables'             => '3 years',
                'hyperdrive_rating'       => '4.0',
                'MGLT'                    => '10',
                'starship_class'          => 'Deep Space Mobile Battlestation',
                'pilots'                  => [],
                'films'                   => [],
                'created'                 => '2014-12-10T16:36:50.509000Z',
                'edited'                  => '2014-12-22T17:35:44.452589Z',
                'url'                     => 'https://swapi.py4e.com/api/starships/9/',
            ], 200),
        ]);

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->getJson('/api/swapi/naves/9');

        $response->assertStatus(200)
            ->assertJsonPath('datos.nombre', 'Death Star')
            ->assertJsonPath('datos.clase_nave', 'Deep Space Mobile Battlestation');

        $datos = $response->json('datos');
        $this->assertArrayNotHasKey('name', $datos);
        $this->assertArrayNotHasKey('starship_class', $datos);
    }

    // -------------------------------------------------------------------------
    // Seguridad – API Key
    // -------------------------------------------------------------------------

    public function test_peticion_sin_api_key_retorna_401(): void
    {
        $response = $this->getJson('/api/swapi/personajes');

        $response->assertStatus(401)
            ->assertJsonPath('success', false);
    }

    public function test_peticion_con_api_key_invalida_retorna_401(): void
    {
        $response = $this->withHeader('X-API-KEY', 'wrong-key')
            ->getJson('/api/swapi/personajes');

        $response->assertStatus(401);
    }

    public function test_listar_peliculas_retorna_estructura_correcta(): void
    {
        Http::fake([
            'swapi.py4e.com/api/films/*' => Http::response([
                'count' => 6,
                'next' => null,
                'previous' => null,
                'results' => [
                    [
                        'title' => 'A New Hope',
                        'episode_id' => 4,
                        'opening_crawl' => 'It is a period of civil war...',
                        'director' => 'George Lucas',
                        'producer' => 'Gary Kurtz, Rick McCallum',
                        'release_date' => '1977-05-25',
                        'characters' => [],
                        'planets' => [],
                        'starships' => [],
                        'vehicles' => [],
                        'species' => [],
                        'created' => '2014-12-10T14:23:31.880000Z',
                        'edited' => '2014-12-20T19:49:45.256000Z',
                        'url' => 'https://swapi.py4e.com/api/films/1/',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->getJson('/api/swapi/peliculas');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'total', 'siguiente', 'anterior', 'datos'])
            ->assertJsonPath('success', true)
            ->assertJsonPath('total', 6);
    }

    public function test_obtener_pelicula_retorna_dto_mapeado(): void
    {
        Http::fake([
            'swapi.py4e.com/api/films/1/' => Http::response([
                'title' => 'A New Hope',
                'episode_id' => 4,
                'opening_crawl' => 'It is a period...',
                'director' => 'George Lucas',
                'producer' => 'Gary Kurtz, Rick McCallum',
                'release_date' => '1977-05-25',
                'characters' => [],
                'planets' => [],
                'starships' => [],
                'vehicles' => [],
                'species' => [],
                'created' => '2014-12-10T14:23:31.880000Z',
                'edited' => '2014-12-20T19:49:45.256000Z',
                'url' => 'https://swapi.py4e.com/api/films/1/',
            ], 200),
        ]);

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->getJson('/api/swapi/peliculas/1');

        $response->assertStatus(200)
            ->assertJsonPath('datos.titulo', 'A New Hope')
            ->assertJsonPath('datos.numero_episodio', 4)
            ->assertJsonPath('datos.director', 'George Lucas')
            ->assertJsonPath('datos.fecha_estreno', '1977-05-25');

        // Verificar que NO contiene claves en inglés
        $datos = $response->json('datos');
        $this->assertArrayNotHasKey('title', $datos);
        $this->assertArrayNotHasKey('episode_id', $datos);
        $this->assertArrayNotHasKey('release_date', $datos);
    }

    public function test_obtener_especie_retorna_dto_mapeado(): void
    {
        Http::fake([
            'swapi.py4e.com/api/species/1/' => Http::response([
                'name' => 'Human',
                'classification' => 'mammal',
                'designation' => 'sentient',
                'average_height' => '180',
                'skin_colors' => 'caucasian, black, asian, hispanic',
                'hair_colors' => 'blonde, brown, black, red',
                'eye_colors' => 'brown, blue, green, hazel, grey, amber',
                'average_lifespan' => '120',
                'homeworld' => 'https://swapi.py4e.com/api/planets/9/',
                'language' => 'Galactic Basic',
                'people' => [],
                'films' => [],
                'created' => '2014-12-10T13:52:11.567000Z',
                'edited' => '2014-12-20T21:36:42.136000Z',
                'url' => 'https://swapi.py4e.com/api/species/1/',
            ], 200),
        ]);

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->getJson('/api/swapi/especies/1');

        $response->assertStatus(200)
            ->assertJsonPath('datos.nombre', 'Human')
            ->assertJsonPath('datos.clasificacion', 'mammal')
            ->assertJsonPath('datos.idioma', 'Galactic Basic');

        $datos = $response->json('datos');
        $this->assertArrayNotHasKey('name', $datos);
        $this->assertArrayNotHasKey('classification', $datos);
    }

    public function test_obtener_vehiculo_retorna_dto_mapeado(): void
    {
        Http::fake([
            'swapi.py4e.com/api/vehicles/4/' => Http::response([
                'name' => 'Sand Crawler',
                'model' => 'Digger Crawler',
                'manufacturer' => 'Corellia Mining Corporation',
                'cost_in_credits' => '150000',
                'length' => '36.8',
                'max_atmosphering_speed' => '30',
                'crew' => '46',
                'passengers' => '30',
                'cargo_capacity' => '50000',
                'consumables' => '2 months',
                'vehicle_class' => 'wheeled',
                'pilots' => [],
                'films' => [],
                'created' => '2014-12-10T15:36:25.724000Z',
                'edited' => '2014-12-20T21:30:21.661000Z',
                'url' => 'https://swapi.py4e.com/api/vehicles/4/',
            ], 200),
        ]);

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->getJson('/api/swapi/vehiculos/4');

        $response->assertStatus(200)
            ->assertJsonPath('datos.nombre', 'Sand Crawler')
            ->assertJsonPath('datos.clase_vehiculo', 'wheeled')
            ->assertJsonPath('datos.fabricante', 'Corellia Mining Corporation');

        $datos = $response->json('datos');
        $this->assertArrayNotHasKey('name', $datos);
        $this->assertArrayNotHasKey('vehicle_class', $datos);
    }

    public function test_cache_evita_segunda_peticion_a_swapi(): void
    {
        Http::fake([
            'swapi.py4e.com/api/people/1/' => Http::response([
                'name' => 'Luke Skywalker',
                'height' => '172',
                'mass' => '77',
                'hair_color' => 'blond',
                'skin_color' => 'fair',
                'eye_color' => 'blue',
                'birth_year' => '19BBY',
                'gender' => 'male',
                'homeworld' => 'https://swapi.py4e.com/api/planets/1/',
                'films' => [], 'species' => [], 'vehicles' => [], 'starships' => [],
                'created' => '2014-12-09T13:50:51.644000Z',
                'edited' => '2014-12-20T21:17:56.891000Z',
                'url' => 'https://swapi.py4e.com/api/people/1/',
            ], 200),
        ]);

        // Primera petición — llama a SWAPI
        $this->withHeader('X-API-KEY', $this->apiKey)->getJson('/api/swapi/personajes/1');
        // Segunda petición — debe servir desde caché
        $this->withHeader('X-API-KEY', $this->apiKey)->getJson('/api/swapi/personajes/1');

        // SWAPI solo debería haber sido llamada UNA vez
        Http::assertSentCount(1);
    }
}
