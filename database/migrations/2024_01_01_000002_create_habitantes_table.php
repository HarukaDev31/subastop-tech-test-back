<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habitantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planeta_id')->constrained('planetas')->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->unsignedSmallInteger('altura')->nullable();
            $table->unsignedSmallInteger('masa')->nullable();
            $table->string('color_cabello', 50)->nullable();
            $table->string('color_piel', 50)->nullable();
            $table->string('color_ojos', 50)->nullable();
            $table->string('anio_nacimiento', 20)->nullable();
            $table->string('genero', 30)->nullable();
            $table->timestamps();

            $table->index('nombre');
            $table->index('planeta_id');
            $table->index('genero');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habitantes');
    }
};
