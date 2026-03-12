<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planetas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('clima', 100)->nullable();
            $table->string('terreno', 100)->nullable();
            $table->unsignedInteger('diametro')->nullable();
            $table->unsignedBigInteger('poblacion')->nullable();
            $table->unsignedSmallInteger('periodo_rotacion')->nullable();
            $table->unsignedSmallInteger('periodo_orbital')->nullable();
            $table->string('gravedad', 50)->nullable();
            $table->string('agua_superficial', 10)->nullable();
            $table->timestamps();

            $table->index('nombre');
            $table->index('clima');
            $table->index('terreno');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planetas');
    }
};
