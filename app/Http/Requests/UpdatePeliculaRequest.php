<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePeliculaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = (int) $this->route('pelicula');

        return [
            'titulo'         => ['sometimes', 'string', 'max:150'],
            'episodio_id'    => ['sometimes', 'integer', 'min:1', Rule::unique('peliculas', 'episodio_id')->ignore($id)],
            'texto_apertura' => ['nullable', 'string', 'max:65535'],
            'director'       => ['sometimes', 'string', 'max:100'],
            'productor'      => ['nullable', 'string', 'max:200'],
            'fecha_estreno' => ['nullable', 'date'],
            'planeta_ids'   => ['nullable', 'array'],
            'planeta_ids.*' => ['integer', 'exists:planetas,id'],
            'nave_ids'      => ['nullable', 'array'],
            'nave_ids.*'    => ['integer', 'exists:naves,id'],
            'vehiculo_ids'  => ['nullable', 'array'],
            'vehiculo_ids.*' => ['integer', 'exists:vehiculos,id'],
            'especie_ids'   => ['nullable', 'array'],
            'especie_ids.*' => ['integer', 'exists:especies,id'],
        ];
    }
}
