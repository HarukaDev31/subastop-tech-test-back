<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Planeta extends Model
{
    use HasFactory;

    protected $table = 'planetas';

    protected $fillable = [
        'nombre',
        'clima',
        'terreno',
        'diametro',
        'poblacion',
        'periodo_rotacion',
        'periodo_orbital',
        'gravedad',
        'agua_superficial',
    ];

    protected $casts = [
        'diametro'         => 'integer',
        'poblacion'        => 'integer',
        'periodo_rotacion' => 'integer',
        'periodo_orbital'  => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function habitantes(): HasMany
    {
        return $this->hasMany(Habitante::class);
    }

    public function especies(): HasMany
    {
        return $this->hasMany(Especie::class, 'planeta_natal_id');
    }

    public function peliculas(): BelongsToMany
    {
        return $this->belongsToMany(Pelicula::class, 'pelicula_planeta');
    }

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    public function scopeNombre(Builder $query, string $nombre): Builder
    {
        return $query->where('nombre', 'like', "%{$nombre}%");
    }

    public function scopeClima(Builder $query, string $clima): Builder
    {
        return $query->where('clima', 'like', "%{$clima}%");
    }

    public function scopeTerreno(Builder $query, string $terreno): Builder
    {
        return $query->where('terreno', 'like', "%{$terreno}%");
    }

    public function scopeRecientes(Builder $query, int $dias = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }
}
