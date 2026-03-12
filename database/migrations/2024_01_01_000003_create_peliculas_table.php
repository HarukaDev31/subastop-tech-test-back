<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peliculas', function (Blueprint $table): void {
            $table->id();
            $table->string('titulo', 150);
            $table->unsignedTinyInteger('episodio_id')->unique();
            $table->string('director', 100);
            $table->string('productor', 200)->nullable();
            $table->date('fecha_estreno')->nullable();
            $table->timestamps();

            $table->index('titulo');
            $table->index('director');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peliculas');
    }
};
