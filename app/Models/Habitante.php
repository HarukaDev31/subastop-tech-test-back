<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Habitante extends Model
{
    use HasFactory;

    protected $table = 'habitantes';

    protected $fillable = [
        'planeta_id',
        'especie_id',
        'nombre',
        'altura',
        'masa',
        'color_cabello',
        'color_piel',
        'color_ojos',
        'anio_nacimiento',
        'genero',
    ];

    protected $casts = [
        'altura' => 'integer',
        'masa'   => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function planeta(): BelongsTo
    {
        return $this->belongsTo(Planeta::class);
    }

    public function especie(): BelongsTo
    {
        return $this->belongsTo(Especie::class);
    }

    public function naves(): BelongsToMany
    {
        return $this->belongsToMany(Nave::class, 'habitante_nave');
    }

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    public function scopeNombre(Builder $query, string $nombre): Builder
    {
        return $query->where('nombre', 'like', "%{$nombre}%");
    }

    public function scopeGenero(Builder $query, string $genero): Builder
    {
        return $query->where('genero', $genero);
    }
}
