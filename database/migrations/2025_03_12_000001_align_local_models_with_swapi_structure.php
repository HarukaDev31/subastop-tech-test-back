<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('naves', function (Blueprint $table): void {
            $table->string('costo_creditos', 50)->nullable();
            $table->string('velocidad_maxima_atmosfera', 50)->nullable();
            $table->string('tripulacion', 50)->nullable();
            $table->string('pasajeros', 50)->nullable();
            $table->string('consumibles', 100)->nullable();
            $table->string('clasificacion_hiperimpulsor', 50)->nullable();
            $table->string('mglt', 50)->nullable();
        });

        Schema::table('peliculas', function (Blueprint $table): void {
            $table->text('texto_apertura')->nullable()->after('episodio_id');
        });

        Schema::table('especies', function (Blueprint $table): void {
            $table->string('designacion', 100)->nullable()->after('nombre');
            $table->string('altura_promedio', 50)->nullable()->after('clasificacion');
            $table->string('colores_piel', 200)->nullable()->after('altura_promedio');
            $table->string('colores_cabello', 200)->nullable()->after('colores_piel');
            $table->string('colores_ojos', 200)->nullable()->after('colores_cabello');
            $table->string('esperanza_vida', 50)->nullable()->after('colores_ojos');
        });

        Schema::table('vehiculos', function (Blueprint $table): void {
            $table->string('costo_creditos', 50)->nullable()->after('nombre');
            $table->string('velocidad_maxima', 50)->nullable()->after('longitud');
            $table->string('tripulacion', 50)->nullable()->after('velocidad_maxima');
            $table->string('pasajeros', 50)->nullable()->after('tripulacion');
            $table->string('capacidad_carga', 50)->nullable()->after('pasajeros');
            $table->string('consumibles', 100)->nullable()->after('capacidad_carga');
        });
    }

    public function down(): void
    {
        Schema::table('naves', function (Blueprint $table): void {
            $table->dropColumn([
                'costo_creditos',
                'velocidad_maxima_atmosfera',
                'tripulacion',
                'pasajeros',
                'consumibles',
                'clasificacion_hiperimpulsor',
                'mglt',
            ]);
        });

        Schema::table('peliculas', function (Blueprint $table): void {
            $table->dropColumn('texto_apertura');
        });

        Schema::table('especies', function (Blueprint $table): void {
            $table->dropColumn([
                'designacion',
                'altura_promedio',
                'colores_piel',
                'colores_cabello',
                'colores_ojos',
                'esperanza_vida',
            ]);
        });

        Schema::table('vehiculos', function (Blueprint $table): void {
            $table->dropColumn([
                'costo_creditos',
                'velocidad_maxima',
                'tripulacion',
                'pasajeros',
                'capacidad_carga',
                'consumibles',
            ]);
        });
    }
};
