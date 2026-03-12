<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = [
        'nombre',
        'modelo',
        'fabricante',
        'clase_vehiculo',
        'longitud',
        'costo_creditos',
        'velocidad_maxima',
        'tripulacion',
        'pasajeros',
        'capacidad_carga',
        'consumibles',
    ];

    protected $casts = [
        'longitud' => 'float',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function peliculas(): BelongsToMany
    {
        return $this->belongsToMany(Pelicula::class, 'pelicula_vehiculo');
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
        return $query->where('clase_vehiculo', 'like', "%{$clase}%");
    }

    public function scopeRecientes(Builder $query, int $dias = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }
}
