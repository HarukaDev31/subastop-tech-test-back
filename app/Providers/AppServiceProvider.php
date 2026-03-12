<?php

namespace App\Providers;

use App\Domain\Local\Planeta\PlanetaRepository;
use App\Domain\Local\Especie\EspecieRepository;
use App\Domain\Local\Nave\NaveRepository;
use App\Domain\Local\Pelicula\PeliculaRepository;
use App\Domain\Local\Vehiculo\VehiculoRepository;
use App\Domain\Local\Habitante\HabitanteRepository;
use App\Infrastructure\Persistence\Eloquent\HabitanteEloquentRepository;
use App\Infrastructure\Persistence\Eloquent\EspecieEloquentRepository;
use App\Infrastructure\Persistence\Eloquent\NaveEloquentRepository;
use App\Infrastructure\Persistence\Eloquent\PeliculaEloquentRepository;
use App\Infrastructure\Persistence\Eloquent\PlanetaEloquentRepository;
use App\Infrastructure\Persistence\Eloquent\VehiculoEloquentRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PlanetaRepository::class, PlanetaEloquentRepository::class);
        $this->app->bind(EspecieRepository::class, EspecieEloquentRepository::class);
        $this->app->bind(NaveRepository::class, NaveEloquentRepository::class);
        $this->app->bind(PeliculaRepository::class, PeliculaEloquentRepository::class);
        $this->app->bind(VehiculoRepository::class, VehiculoEloquentRepository::class);
        $this->app->bind(HabitanteRepository::class, HabitanteEloquentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
