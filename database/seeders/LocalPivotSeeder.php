<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalPivotSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding pivot tables (relaciones N:N)…');

        $peliculaIds = DB::table('peliculas')->pluck('id')->all();
        $planetaIds  = DB::table('planetas')->pluck('id')->all();
        $naveIds     = DB::table('naves')->pluck('id')->all();
        $especieIds  = DB::table('especies')->pluck('id')->all();
        $vehiculoIds = DB::table('vehiculos')->pluck('id')->all();
        $habitanteIds = DB::table('habitantes')->pluck('id')->all();

        if ($peliculaIds === [] || $planetaIds === []) {
            $this->command->warn('Faltan películas o planetas. Saltando pivots.');

            return;
        }

        $this->seedPeliculaPlaneta($peliculaIds, $planetaIds);
        $this->seedNavePelicula($naveIds, $peliculaIds);
        $this->seedEspeciePelicula($especieIds, $peliculaIds);
        $this->seedPeliculaVehiculo($peliculaIds, $vehiculoIds);
        $this->seedHabitanteNave($habitanteIds, $naveIds);

        $this->command->info('Pivot tables seeded.');
    }

    private function seedPeliculaPlaneta(array $peliculaIds, array $planetaIds): void
    {
        $rows = [];
        $seen = [];
        foreach ($peliculaIds as $pid) {
            $n = random_int(1, min(8, count($planetaIds)));
            $chosen = (array) array_rand(array_flip($planetaIds), $n);
            foreach ($chosen as $planetaId) {
                $key = "{$pid}_{$planetaId}";
                if (! isset($seen[$key])) {
                    $seen[$key] = true;
                    $rows[] = ['pelicula_id' => $pid, 'planeta_id' => $planetaId];
                }
            }
            if (count($rows) >= 2000) {
                DB::table('pelicula_planeta')->insert($rows);
                $rows = [];
            }
        }
        if ($rows !== []) {
            DB::table('pelicula_planeta')->insert($rows);
        }
    }

    private function seedNavePelicula(array $naveIds, array $peliculaIds): void
    {
        $rows = [];
        $seen = [];
        foreach ($naveIds as $nid) {
            $n = random_int(1, min(5, count($peliculaIds)));
            $chosen = (array) array_rand(array_flip($peliculaIds), $n);
            foreach ($chosen as $pid) {
                $key = "{$nid}_{$pid}";
                if (! isset($seen[$key])) {
                    $seen[$key] = true;
                    $rows[] = ['nave_id' => $nid, 'pelicula_id' => $pid];
                }
            }
            if (count($rows) >= 2000) {
                DB::table('nave_pelicula')->insert($rows);
                $rows = [];
            }
        }
        if ($rows !== []) {
            DB::table('nave_pelicula')->insert($rows);
        }
    }

    private function seedEspeciePelicula(array $especieIds, array $peliculaIds): void
    {
        $rows = [];
        $seen = [];
        foreach ($especieIds as $eid) {
            $n = random_int(0, min(4, count($peliculaIds)));
            if ($n === 0) {
                continue;
            }
            $chosen = (array) array_rand(array_flip($peliculaIds), $n);
            foreach ($chosen as $pid) {
                $key = "{$eid}_{$pid}";
                if (! isset($seen[$key])) {
                    $seen[$key] = true;
                    $rows[] = ['especie_id' => $eid, 'pelicula_id' => $pid];
                }
            }
            if (count($rows) >= 2000) {
                DB::table('especie_pelicula')->insert($rows);
                $rows = [];
            }
        }
        if ($rows !== []) {
            DB::table('especie_pelicula')->insert($rows);
        }
    }

    private function seedPeliculaVehiculo(array $peliculaIds, array $vehiculoIds): void
    {
        if ($vehiculoIds === []) {
            return;
        }
        $rows = [];
        $seen = [];
        foreach ($peliculaIds as $pid) {
            $n = random_int(0, min(5, count($vehiculoIds)));
            if ($n === 0) {
                continue;
            }
            $chosen = (array) array_rand(array_flip($vehiculoIds), $n);
            foreach ($chosen as $vid) {
                $key = "{$pid}_{$vid}";
                if (! isset($seen[$key])) {
                    $seen[$key] = true;
                    $rows[] = ['pelicula_id' => $pid, 'vehiculo_id' => $vid];
                }
            }
            if (count($rows) >= 2000) {
                DB::table('pelicula_vehiculo')->insert($rows);
                $rows = [];
            }
        }
        if ($rows !== []) {
            DB::table('pelicula_vehiculo')->insert($rows);
        }
    }

    private function seedHabitanteNave(array $habitanteIds, array $naveIds): void
    {
        if ($naveIds === []) {
            return;
        }
        $rows = [];
        $seen = [];
        foreach ($naveIds as $nid) {
            $n = random_int(0, min(4, count($habitanteIds)));
            if ($n === 0) {
                continue;
            }
            $chosen = (array) array_rand(array_flip($habitanteIds), $n);
            foreach ($chosen as $hid) {
                $key = "{$hid}_{$nid}";
                if (! isset($seen[$key])) {
                    $seen[$key] = true;
                    $rows[] = ['habitante_id' => $hid, 'nave_id' => $nid];
                }
            }
            if (count($rows) >= 2000) {
                DB::table('habitante_nave')->insert($rows);
                $rows = [];
            }
        }
        if ($rows !== []) {
            DB::table('habitante_nave')->insert($rows);
        }
    }
}
