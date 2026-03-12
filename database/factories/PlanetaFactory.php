<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Planeta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Planeta>
 */
class PlanetaFactory extends Factory
{
    protected $model = Planeta::class;

    public function definition(): array
    {
        return [
            'nombre'           => $this->faker->unique()->word() . ' ' . $this->faker->word(),
            'clima'            => $this->faker->randomElement(['árido', 'templado', 'frío', 'tropical', 'ventoso']),
            'terreno'          => $this->faker->randomElement(['desierto', 'montañas', 'océano', 'llanuras', 'bosque']),
            'diametro'         => $this->faker->numberBetween(1000, 200000),
            'poblacion'        => $this->faker->numberBetween(0, 10_000_000_000),
            'periodo_rotacion' => $this->faker->numberBetween(10, 100),
            'periodo_orbital'  => $this->faker->numberBetween(100, 1000),
            'gravedad'         => $this->faker->randomElement(['1 standard', '0.5 standard', '2 standard']),
            'agua_superficial' => (string) $this->faker->numberBetween(0, 100),
        ];
    }
}
