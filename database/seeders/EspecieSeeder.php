<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspecieSeeder extends Seeder
{
    private const COUNT = 3000;

    private const CHUNK = 500;

    public function run(): void
    {
        $this->command->info('Seeding ' . self::COUNT . ' especies…');

        $faker = \Faker\Factory::create('es_ES');
        $faker->unique(true);

        $planetaIds = DB::table('planetas')->pluck('id')->all();
        if ($planetaIds === []) {
            $this->command->warn('No hay planetas. Ejecuta PlanetaSeeder antes.');

            return;
        }

        $rows = [];
        for ($i = 1; $i <= self::COUNT; $i++) {
            $rows[] = [
                'nombre'            => $faker->unique()->regexify('Especie-[A-Z][a-z]{4,8}-[0-9]{3}'),
                'designacion'       => $faker->optional(0.5)->randomElement(['reptiliano', 'mamífero', 'insectoide', 'desconocido']),
                'clasificacion'     => $faker->optional(0.7)->randomElement(['mamífero', 'reptil', 'anfibio', 'insecto', 'desconocido']),
                'altura_promedio'   => $faker->optional(0.5)->randomElement(['50', '100', '150', '200', 'desconocido']),
                'colores_piel'      => $faker->optional(0.5)->randomElement(['verde', 'azul', 'gris', 'marrón', 'blanco']),
                'colores_cabello'   => $faker->optional(0.4)->randomElement(['negro', 'castaño', 'rubio', 'ninguno']),
                'colores_ojos'      => $faker->optional(0.4)->randomElement(['amarillo', 'negro', 'azul', 'rojo']),
                'esperanza_vida'    => $faker->optional(0.4)->randomElement(['50', '100', '200', 'desconocido']),
                'idioma'            => $faker->optional(0.6)->languageCode(),
                'planeta_natal_id'  => $faker->optional(0.8)->randomElement($planetaIds),
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
            if (count($rows) >= self::CHUNK) {
                DB::table('especies')->insert($rows);
                $rows = [];
            }
        }
        if ($rows !== []) {
            DB::table('especies')->insert($rows);
        }

        $this->command->info('Especies seeded.');
    }
}
