<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'          => ['sometimes', 'string', 'max:100'],
            'modelo'          => ['nullable', 'string', 'max:150'],
            'fabricante'      => ['nullable', 'string', 'max:200'],
            'clase_nave'      => ['nullable', 'string', 'max:100'],
            'longitud'        => ['nullable', 'numeric', 'min:0'],
            'capacidad_carga' => ['nullable', 'integer', 'min:0'],
            'costo_creditos'  => ['nullable', 'string', 'max:50'],
            'velocidad_maxima_atmosfera' => ['nullable', 'string', 'max:50'],
            'tripulacion'     => ['nullable', 'string', 'max:50'],
            'pasajeros'       => ['nullable', 'string', 'max:50'],
            'consumibles'     => ['nullable', 'string', 'max:100'],
            'clasificacion_hiperimpulsor' => ['nullable', 'string', 'max:50'],
            'mglt'            => ['nullable', 'string', 'max:50'],
            'piloto_ids'      => ['nullable', 'array'],
            'piloto_ids.*'    => ['integer', 'exists:habitantes,id'],
            'pelicula_ids'    => ['nullable', 'array'],
            'pelicula_ids.*'  => ['integer', 'exists:peliculas,id'],
        ];
    }
}
