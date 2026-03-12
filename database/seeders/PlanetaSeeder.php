<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Planeta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanetaSeeder extends Seeder
{
    private const COUNT = 2000;

    private const CHUNK = 500;

    public function run(): void
    {
        $this->command->info('Seeding ' . self::COUNT . ' planetas…');

        $faker = \Faker\Factory::create('es_ES');
        $faker->unique(true);

        $rows = [];
        for ($i = 1; $i <= self::COUNT; $i++) {
            $rows[] = [
                'nombre'             => $faker->unique()->regexify('Planeta-[A-Z]{2}[0-9]{4}-[A-Z]'),
                'clima'              => $faker->randomElement(['árido', 'templado', 'tropical', 'congelado', 'desconocido', 'artificial']),
                'terreno'              => $faker->randomElement(['desierto', 'jungla', 'montañas', 'océanos', 'tundra', 'volcánico', 'urbano']),
                'diametro'           => $faker->optional(0.7)->numberBetween(1000, 200000),
                'poblacion'          => $faker->optional(0.6)->numberBetween(0, 10000000000),
                'periodo_rotacion'   => $faker->optional(0.5)->numberBetween(1, 500),
                'periodo_orbital'    => $faker->optional(0.5)->numberBetween(100, 10000),
                'gravedad'           => $faker->optional(0.5)->randomElement(['0.5', '1 standard', '1.5', '2']),
                'agua_superficial'   => $faker->optional(0.4)->randomElement(['0', '10', '40', '100']),
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
            if (count($rows) >= self::CHUNK) {
                DB::table('planetas')->insert($rows);
                $rows = [];
            }
        }
        if ($rows !== []) {
            DB::table('planetas')->insert($rows);
        }

        $this->command->info('Planetas seeded.');
    }
}
