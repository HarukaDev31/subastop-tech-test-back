<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('naves', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 100);
            $table->string('modelo', 150)->nullable();
            $table->string('fabricante', 200)->nullable();
            $table->string('clase_nave', 100)->nullable();
            $table->decimal('longitud', 10, 2)->nullable();
            $table->unsignedBigInteger('capacidad_carga')->nullable();
            $table->timestamps();

            $table->index('nombre');
            $table->index('clase_nave');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('naves');
    }
};
