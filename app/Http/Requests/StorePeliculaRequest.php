<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeliculaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo'         => ['required', 'string', 'max:150'],
            'episodio_id'    => ['required', 'integer', 'min:1', 'unique:peliculas,episodio_id'],
            'texto_apertura' => ['nullable', 'string', 'max:65535'],
            'director'       => ['required', 'string', 'max:100'],
            'productor'     => ['nullable', 'string', 'max:200'],
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

    public function messages(): array
    {
        return [
            'titulo.required'       => 'El título es obligatorio.',
            'episodio_id.required'  => 'El número de episodio es obligatorio.',
            'episodio_id.unique'    => 'Ya existe una película con ese número de episodio.',
            'director.required'     => 'El director es obligatorio.',
            'planeta_ids.*.exists'  => 'Uno o más planetas no existen.',
            'nave_ids.*.exists'     => 'Una o más naves no existen.',
            'vehiculo_ids.*.exists' => 'Uno o más vehículos no existen.',
            'especie_ids.*.exists'  => 'Una o más especies no existen.',
        ];
    }
}
