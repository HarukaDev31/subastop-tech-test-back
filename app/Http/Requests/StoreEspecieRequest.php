<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEspecieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'            => ['required', 'string', 'max:100', 'unique:especies,nombre'],
            'designacion'       => ['nullable', 'string', 'max:100'],
            'clasificacion'      => ['nullable', 'string', 'max:100'],
            'altura_promedio'    => ['nullable', 'string', 'max:50'],
            'colores_piel'       => ['nullable', 'string', 'max:200'],
            'colores_cabello'    => ['nullable', 'string', 'max:200'],
            'colores_ojos'       => ['nullable', 'string', 'max:200'],
            'esperanza_vida'     => ['nullable', 'string', 'max:50'],
            'idioma'             => ['nullable', 'string', 'max:100'],
            'planeta_natal_id' => ['nullable', 'integer', 'exists:planetas,id'],
            'pelicula_ids'     => ['nullable', 'array'],
            'pelicula_ids.*'   => ['integer', 'exists:peliculas,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'          => 'El nombre de la especie es obligatorio.',
            'nombre.unique'            => 'Ya existe una especie con ese nombre.',
            'planeta_natal_id.exists'  => 'El planeta natal no existe.',
            'pelicula_ids.*.exists'    => 'Una o más películas no existen.',
        ];
    }
}
