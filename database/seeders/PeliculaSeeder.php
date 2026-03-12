<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeliculaSeeder extends Seeder
{
    /** Máximo 255: episodio_id es unsignedTinyInteger en la BD */
    private const COUNT = 255;

    private const CHUNK = 200;

    public function run(): void
    {
        $this->command->info('Seeding ' . self::COUNT . ' películas…');

        $faker = \Faker\Factory::create('es_ES');

        $rows = [];
        for ($ep = 1; $ep <= self::COUNT; $ep++) {
            $rows[] = [
                'titulo'         => $faker->sentence(3),
                'episodio_id'    => $ep,
                'texto_apertura' => $faker->optional(0.6)->paragraph(2),
                'director'       => $faker->name(),
                'productor'      => $faker->optional(0.7)->name(),
                'fecha_estreno'  => $faker->optional(0.8)->dateTimeBetween('-50 years')?->format('Y-m-d'),
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
            if (count($rows) >= self::CHUNK) {
                DB::table('peliculas')->insert($rows);
                $rows = [];
            }
        }
        if ($rows !== []) {
            DB::table('peliculas')->insert($rows);
        }

        $this->command->info('Películas seeded.');
    }
}
