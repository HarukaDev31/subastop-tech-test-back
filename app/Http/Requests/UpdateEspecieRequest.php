<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEspecieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = (int) $this->route('especy');

        return [
            'nombre'            => ['sometimes', 'string', 'max:100', Rule::unique('especies', 'nombre')->ignore($id)],
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
}
