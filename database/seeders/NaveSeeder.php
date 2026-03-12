<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NaveSeeder extends Seeder
{
    private const COUNT = 2000;

    private const CHUNK = 500;

    public function run(): void
    {
        $this->command->info('Seeding ' . self::COUNT . ' naves…');

        $faker = \Faker\Factory::create('es_ES');

        $rows = [];
        for ($i = 1; $i <= self::COUNT; $i++) {
            $rows[] = [
                'nombre'                      => $faker->regexify('[A-Z][a-z]{2,8}-[0-9]{2,4}'),
                'modelo'                      => $faker->optional(0.7)->regexify('[A-Z]{2}-[0-9]{2}'),
                'fabricante'                  => $faker->optional(0.6)->company(),
                'clase_nave'                  => $faker->optional(0.7)->randomElement(['caza estelar', 'transporte', 'crucero', 'corbeta', 'destructor']),
                'longitud'                    => $faker->optional(0.6)->randomFloat(2, 5, 5000),
                'capacidad_carga'             => $faker->optional(0.5)->numberBetween(0, 1000000),
                'costo_creditos'              => $faker->optional(0.4)->numerify('######'),
                'velocidad_maxima_atmosfera'  => $faker->optional(0.4)->numerify('####'),
                'tripulacion'                 => $faker->optional(0.4)->numerify('#-#'),
                'pasajeros'                   => $faker->optional(0.4)->numerify('###'),
                'consumibles'                 => $faker->optional(0.3)->randomElement(['1 mes', '6 meses', '1 año']),
                'clasificacion_hiperimpulsor' => $faker->optional(0.3)->numerify('#.#'),
                'mglt'                        => $faker->optional(0.3)->numerify('###'),
                'created_at'                  => now(),
                'updated_at'                  => now(),
            ];
            if (count($rows) >= self::CHUNK) {
                DB::table('naves')->insert($rows);
                $rows = [];
            }
        }
        if ($rows !== []) {
            DB::table('naves')->insert($rows);
        }

        $this->command->info('Naves seeded.');
    }
}
