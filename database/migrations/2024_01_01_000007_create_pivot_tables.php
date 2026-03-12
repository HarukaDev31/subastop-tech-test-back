<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pelicula ↔ Planeta  (many-to-many)
        Schema::create('pelicula_planeta', function (Blueprint $table): void {
            $table->foreignId('pelicula_id')->constrained('peliculas')->cascadeOnDelete();
            $table->foreignId('planeta_id')->constrained('planetas')->cascadeOnDelete();
            $table->primary(['pelicula_id', 'planeta_id']);
        });

        // Nave ↔ Pelicula  (many-to-many)
        Schema::create('nave_pelicula', function (Blueprint $table): void {
            $table->foreignId('nave_id')->constrained('naves')->cascadeOnDelete();
            $table->foreignId('pelicula_id')->constrained('peliculas')->cascadeOnDelete();
            $table->primary(['nave_id', 'pelicula_id']);
        });

        // Especie ↔ Pelicula  (many-to-many)
        Schema::create('especie_pelicula', function (Blueprint $table): void {
            $table->foreignId('especie_id')->constrained('especies')->cascadeOnDelete();
            $table->foreignId('pelicula_id')->constrained('peliculas')->cascadeOnDelete();
            $table->primary(['especie_id', 'pelicula_id']);
        });

        // Pelicula ↔ Vehiculo  (many-to-many)
        Schema::create('pelicula_vehiculo', function (Blueprint $table): void {
            $table->foreignId('pelicula_id')->constrained('peliculas')->cascadeOnDelete();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete();
            $table->primary(['pelicula_id', 'vehiculo_id']);
        });

        // Habitante ↔ Nave  (pilotos, many-to-many)
        Schema::create('habitante_nave', function (Blueprint $table): void {
            $table->foreignId('habitante_id')->constrained('habitantes')->cascadeOnDelete();
            $table->foreignId('nave_id')->constrained('naves')->cascadeOnDelete();
            $table->primary(['habitante_id', 'nave_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habitante_nave');
        Schema::dropIfExists('pelicula_vehiculo');
        Schema::dropIfExists('especie_pelicula');
        Schema::dropIfExists('nave_pelicula');
        Schema::dropIfExists('pelicula_planeta');
    }
};
