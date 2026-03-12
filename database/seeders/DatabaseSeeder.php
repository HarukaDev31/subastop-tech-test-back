<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call([
            PlanetaSeeder::class,
            PeliculaSeeder::class,
            EspecieSeeder::class,
            HabitanteSeeder::class,
            NaveSeeder::class,
            VehiculoSeeder::class,
            LocalPivotSeeder::class,
        ]);
    }
}
