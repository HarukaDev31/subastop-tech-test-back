<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pelicula extends Model
{
    use HasFactory;

    protected $table = 'peliculas';

    protected $fillable = [
        'titulo',
        'episodio_id',
        'texto_apertura',
        'director',
        'productor',
        'fecha_estreno',
    ];

    protected $casts = [
        'episodio_id'  => 'integer',
        'fecha_estreno' => 'date',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function planetas(): BelongsToMany
    {
        return $this->belongsToMany(Planeta::class, 'pelicula_planeta');
    }

    public function naves(): BelongsToMany
    {
        return $this->belongsToMany(Nave::class, 'nave_pelicula');
    }

    public function vehiculos(): BelongsToMany
    {
        return $this->belongsToMany(Vehiculo::class, 'pelicula_vehiculo');
    }

    public function especies(): BelongsToMany
    {
        return $this->belongsToMany(Especie::class, 'especie_pelicula');
    }

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    public function scopeTitulo(Builder $query, string $titulo): Builder
    {
        return $query->where('titulo', 'like', "%{$titulo}%");
    }

    public function scopeDirector(Builder $query, string $director): Builder
    {
        return $query->where('director', 'like', "%{$director}%");
    }

    public function scopeRecientes(Builder $query, int $dias = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }
}
