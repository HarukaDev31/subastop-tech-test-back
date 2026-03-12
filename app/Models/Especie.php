<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Especie extends Model
{
    use HasFactory;

    protected $table = 'especies';

    protected $fillable = [
        'nombre',
        'designacion',
        'clasificacion',
        'altura_promedio',
        'colores_piel',
        'colores_cabello',
        'colores_ojos',
        'esperanza_vida',
        'idioma',
        'planeta_natal_id',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function planetaNatal(): BelongsTo
    {
        return $this->belongsTo(Planeta::class, 'planeta_natal_id');
    }

    public function habitantes(): HasMany
    {
        return $this->hasMany(Habitante::class);
    }

    public function peliculas(): BelongsToMany
    {
        return $this->belongsToMany(Pelicula::class, 'especie_pelicula');
    }

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    public function scopeNombre(Builder $query, string $nombre): Builder
    {
        return $query->where('nombre', 'like', "%{$nombre}%");
    }

    public function scopeClasificacion(Builder $query, string $clasificacion): Builder
    {
        return $query->where('clasificacion', 'like', "%{$clasificacion}%");
    }

    public function scopeRecientes(Builder $query, int $dias = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }
}
