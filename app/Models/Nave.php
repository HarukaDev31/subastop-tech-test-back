<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Nave extends Model
{
    use HasFactory;

    protected $table = 'naves';

    protected $fillable = [
        'nombre',
        'modelo',
        'fabricante',
        'clase_nave',
        'longitud',
        'capacidad_carga',
        'costo_creditos',
        'velocidad_maxima_atmosfera',
        'tripulacion',
        'pasajeros',
        'consumibles',
        'clasificacion_hiperimpulsor',
        'mglt',
    ];

    protected $casts = [
        'longitud'        => 'float',
        'capacidad_carga' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function peliculas(): BelongsToMany
    {
        return $this->belongsToMany(Pelicula::class, 'nave_pelicula');
    }

    public function pilotos(): BelongsToMany
    {
        return $this->belongsToMany(Habitante::class, 'habitante_nave');
    }

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    public function scopeNombre(Builder $query, string $nombre): Builder
    {
        return $query->where('nombre', 'like', "%{$nombre}%");
    }

    public function scopeClase(Builder $query, string $clase): Builder
    {
        return $query->where('clase_nave', 'like', "%{$clase}%");
    }

    public function scopeRecientes(Builder $query, int $dias = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }
}
