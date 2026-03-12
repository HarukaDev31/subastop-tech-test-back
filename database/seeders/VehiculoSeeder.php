<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiculoSeeder extends Seeder
{
    private const COUNT = 1500;

    private const CHUNK = 500;

    public function run(): void
    {
        $this->command->info('Seeding ' . self::COUNT . ' vehículos…');

        $faker = \Faker\Factory::create('es_ES');

        $rows = [];
        for ($i = 1; $i <= self::COUNT; $i++) {
            $rows[] = [
                'nombre'           => $faker->regexify('[A-Z][a-z]{2,10} [0-9]{0,2}'),
                'modelo'           => $faker->optional(0.6)->regexify('[A-Z]{2}-[0-9]{2}'),
                'fabricante'       => $faker->optional(0.5)->company(),
                'clase_vehiculo'   => $faker->optional(0.6)->randomElement(['ruedas', 'reptante', 'aéreo', 'acuático']),
                'longitud'         => $faker->optional(0.5)->randomFloat(2, 2, 100),
                'costo_creditos'   => $faker->optional(0.3)->numerify('#####'),
                'velocidad_maxima' => $faker->optional(0.4)->numerify('###'),
                'tripulacion'      => $faker->optional(0.4)->numerify('#-#'),
                'pasajeros'        => $faker->optional(0.4)->numerify('##'),
                'capacidad_carga'  => $faker->optional(0.3)->numerify('####'),
                'consumibles'      => $faker->optional(0.3)->randomElement(['1 día', '1 semana']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
            if (count($rows) >= self::CHUNK) {
                DB::table('vehiculos')->insert($rows);
                $rows = [];
            }
        }
        if ($rows !== []) {
            DB::table('vehiculos')->insert($rows);
        }

        $this->command->info('Vehículos seeded.');
    }
}
