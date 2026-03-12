<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Habitante;
use App\Models\Planeta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HabitanteTest extends TestCase
{
    use RefreshDatabase;

    private string $apiKey = 'test-api-key-123';

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.api_key' => $this->apiKey]);
    }

    private function planeta(): Planeta
    {
        return Planeta::factory()->create();
    }

    public function test_listar_habitantes_retorna_paginado(): void
    {
        $planeta = $this->planeta();
        Habitante::factory()->count(3)->create(['planeta_id' => $planeta->id]);

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->getJson('/api/habitantes');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(3, 'data');
    }

    public function test_crear_habitante_asociado_a_planeta(): void
    {
        $planeta = $this->planeta();

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->postJson('/api/habitantes', [
                'planeta_id'      => $planeta->id,
                'nombre'          => 'Obi-Wan Kenobi',
                'genero'          => 'masculino',
                'altura'          => 182,
                'masa'            => 77,
                'color_cabello'   => 'castano',
                'color_ojos'      => 'azul',
                'anio_nacimiento' => '57BBY',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Obi-Wan Kenobi');

        $this->assertDatabaseHas('habitantes', [
            'nombre'     => 'Obi-Wan Kenobi',
            'planeta_id' => $planeta->id,
        ]);
    }

    public function test_crear_habitante_falla_con_planeta_inexistente(): void
    {
        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->postJson('/api/habitantes', [
                'planeta_id' => 9999,
                'nombre'     => 'Test',
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    public function test_crear_habitante_requiere_nombre(): void
    {
        $planeta = $this->planeta();

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->postJson('/api/habitantes', [
                'planeta_id' => $planeta->id,
            ]);

        $response->assertStatus(422);
    }

    public function test_obtener_habitante_por_id(): void
    {
        $planeta = $this->planeta();
        $habitante = Habitante::factory()->create(['planeta_id' => $planeta->id]);

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->getJson("/api/habitantes/{$habitante->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.name', $habitante->nombre);
    }

    public function test_habitante_no_encontrado_retorna_404(): void
    {
        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->getJson('/api/habitantes/9999');

        $response->assertStatus(404);
    }

    public function test_eliminar_habitante(): void
    {
        $planeta = $this->planeta();
        $habitante = Habitante::factory()->create(['planeta_id' => $planeta->id]);

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->deleteJson("/api/habitantes/{$habitante->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('habitantes', ['id' => $habitante->id]);
    }

    public function test_habitantes_incluyen_planeta_en_respuesta(): void
    {
        $planeta = $this->planeta();
        $habitante = Habitante::factory()->create(['planeta_id' => $planeta->id]);

        $response = $this->withHeader('X-API-KEY', $this->apiKey)
            ->getJson("/api/habitantes/{$habitante->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['planet']]);
    }
}
