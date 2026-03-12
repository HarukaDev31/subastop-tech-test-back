<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HabitanteSeeder extends Seeder
{
    private const COUNT = 10000;

    private const CHUNK = 1000;

    private const GENEROS = ['masculino', 'femenino', 'hermafrodita', 'sin género', 'desconocido'];

    public function run(): void
    {
        $this->command->info('Seeding ' . self::COUNT . ' habitantes…');

        $faker = \Faker\Factory::create('es_ES');

        $planetaIds = DB::table('planetas')->pluck('id')->all();
        $especieIds = DB::table('especies')->pluck('id')->all();
        if ($planetaIds === []) {
            $this->command->warn('No hay planetas. Ejecuta PlanetaSeeder antes.');

            return;
        }

        $rows = [];
        for ($i = 1; $i <= self::COUNT; $i++) {
            $rows[] = [
                'planeta_id'       => $faker->randomElement($planetaIds),
                'especie_id'      => $faker->optional(0.6)->randomElement($especieIds),
                'nombre'          => $faker->name(),
                'altura'          => $faker->optional(0.8)->numberBetween(80, 250),
                'masa'            => $faker->optional(0.7)->numberBetween(20, 200),
                'color_cabello'   => $faker->optional(0.6)->randomElement(['negro', 'castaño', 'rubio', 'pelirrojo', 'gris', 'ninguno']),
                'color_piel'      => $faker->optional(0.6)->randomElement(['claro', 'oscuro', 'verde', 'azul', 'gris']),
                'color_ojos'      => $faker->optional(0.5)->randomElement(['marrón', 'azul', 'verde', 'negro', 'amarillo']),
                'anio_nacimiento' => $faker->optional(0.5)->regexify('[0-9]{1,2}(BBY|ABY)'),
                'genero'          => $faker->randomElement(self::GENEROS),
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            if (count($rows) >= self::CHUNK) {
                DB::table('habitantes')->insert($rows);
                $rows = [];
            }
        }
        if ($rows !== []) {
            DB::table('habitantes')->insert($rows);
        }

        $this->command->info('Habitantes seeded.');
    }
}
