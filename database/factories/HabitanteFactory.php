<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Habitante;
use App\Models\Planeta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Habitante>
 */
class HabitanteFactory extends Factory
{
    protected $model = Habitante::class;

    public function definition(): array
    {
        return [
            'planeta_id'      => Planeta::factory(),
            'nombre'          => $this->faker->name(),
            'altura'          => $this->faker->numberBetween(100, 250),
            'masa'            => $this->faker->numberBetween(40, 200),
            'color_cabello'   => $this->faker->randomElement(['negro', 'rubio', 'castaño', 'rojo', 'calvo']),
            'color_piel'      => $this->faker->randomElement(['claro', 'oscuro', 'verde', 'azul', 'rojo']),
            'color_ojos'      => $this->faker->randomElement(['azul', 'marrón', 'verde', 'amarillo', 'rojo']),
            'anio_nacimiento' => $this->faker->numberBetween(1, 900) . 'BBY',
            'genero'          => $this->faker->randomElement(['masculino', 'femenino', 'desconocido']),
        ];
    }
}
