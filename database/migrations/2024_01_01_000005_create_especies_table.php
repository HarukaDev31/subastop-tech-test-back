<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('especies', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('clasificacion', 100)->nullable();
            $table->string('idioma', 100)->nullable();
            $table->foreignId('planeta_natal_id')
                ->nullable()
                ->constrained('planetas')
                ->nullOnDelete();
            $table->timestamps();

            $table->index('clasificacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('especies');
    }
};
