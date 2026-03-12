<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Planeta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginationTest extends TestCase
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

    public function test_page_y_per_page_funcionan_en_listado_local(): void
    {
        Planeta::factory()->count(30)->create();

        $response = $this->withHeaders($this->headers())
            ->getJson('/api/planetas?page=2&per_page=10');

        $response->assertStatus(200)
            ->assertJsonPath('meta.current_page', 2)
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonPath('meta.total', 30);

        $this->assertCount(10, $response->json('data'));
    }
}

