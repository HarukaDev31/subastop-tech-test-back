<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Habitante;
use App\Models\Planeta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanetaTest extends TestCase
{
    use RefreshDatabase;

    private string $apiKey = 'test-api-key-123';

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.api_key' => $this->apiKey]);
    }

    private function headers(): array
    {
        return ['X-API-KEY' => $this->apiKey];
    }

    // -------------------------------------------------------------------------
    // GET /api/planetas
    // -------------------------------------------------------------------------

    public function test_listar_planetas_retorna_paginado(): void
    {
        Planeta::factory()->count(3)->create();

        $response = $this->withHeaders($this->headers())->getJson('/api/planetas');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta' => ['total', 'per_page', 'current_page', 'last_page'],
            ])
            ->assertJsonPath('meta.total', 3);
    }

    public function test_filtrar_planetas_por_nombre(): void
    {
        Planeta::factory()->create(['nombre' => 'Tatooine']);
        Planeta::factory()->create(['nombre' => 'Alderaan']);

        $response = $this->withHeaders($this->headers())
            ->getJson('/api/planetas?nombre=Tat');

        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.name', 'Tatooine');
    }

    // -------------------------------------------------------------------------
    // POST /api/planetas
    // -------------------------------------------------------------------------

    public function test_crear_planeta_con_habitantes(): void
    {
        $payload = [
            'nombre'    => 'Tatooine',
            'clima'     => 'árido',
            'terreno'   => 'desierto',
            'diametro'  => 10465,
            'poblacion' => 200000,
            'habitantes' => [
                [
                    'nombre'          => 'Luke Skywalker',
                    'altura'          => 172,
                    'masa'            => 77,
                    'color_cabello'   => 'rubio',
                    'color_ojos'      => 'azul',
                    'genero'          => 'masculino',
                ],
            ],
        ];

        $response = $this->withHeaders($this->headers())
            ->postJson('/api/planetas', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Tatooine');

        $this->assertDatabaseHas('planetas', ['nombre' => 'Tatooine']);
        $this->assertDatabaseHas('habitantes', ['nombre' => 'Luke Skywalker']);
    }

    public function test_crear_planeta_falla_con_nombre_duplicado(): void
    {
        Planeta::factory()->create(['nombre' => 'Tatooine']);

        $response = $this->withHeaders($this->headers())
            ->postJson('/api/planetas', ['nombre' => 'Tatooine']);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    public function test_crear_planeta_requiere_nombre(): void
    {
        $response = $this->withHeaders($this->headers())
            ->postJson('/api/planetas', []);

        $response->assertStatus(422)
            ->assertJsonStructure(['errores' => ['nombre']]);
    }

    // -------------------------------------------------------------------------
    // GET /api/planetas/{id}
    // -------------------------------------------------------------------------

    public function test_obtener_planeta_con_habitantes_eager_loading(): void
    {
        $planeta   = Planeta::factory()->create();
        Habitante::factory()->count(2)->create(['planeta_id' => $planeta->id]);

        $response = $this->withHeaders($this->headers())
            ->getJson("/api/planetas/{$planeta->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'name', 'inhabitants']]);

        $this->assertCount(2, $response->json('data.inhabitants'));
    }

    public function test_planeta_no_encontrado_retorna_404(): void
    {
        $response = $this->withHeaders($this->headers())
            ->getJson('/api/planetas/9999');

        $response->assertStatus(404);
    }

    // -------------------------------------------------------------------------
    // PUT /api/planetas/{id}
    // -------------------------------------------------------------------------

    public function test_actualizar_planeta(): void
    {
        $planeta = Planeta::factory()->create(['nombre' => 'Alderaan']);

        $response = $this->withHeaders($this->headers())
            ->putJson("/api/planetas/{$planeta->id}", ['clima' => 'templado']);

        $response->assertStatus(200)
            ->assertJsonPath('data.climate', 'templado');
    }

    // -------------------------------------------------------------------------
    // DELETE /api/planetas/{id}
    // -------------------------------------------------------------------------

    public function test_eliminar_planeta(): void
    {
        $planeta = Planeta::factory()->create();

        $response = $this->withHeaders($this->headers())
            ->deleteJson("/api/planetas/{$planeta->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('planetas', ['id' => $planeta->id]);
    }
}
