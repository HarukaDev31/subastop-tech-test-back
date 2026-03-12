<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHabitanteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'          => ['required', 'string', 'max:100'],
            'planeta_id'      => ['required', 'integer', 'exists:planetas,id'],
            'especie_id'      => ['nullable', 'integer', 'exists:especies,id'],
            'altura'          => ['nullable', 'integer', 'min:0', 'max:500'],
            'masa'            => ['nullable', 'integer', 'min:0', 'max:1000'],
            'color_cabello'   => ['nullable', 'string', 'max:50'],
            'color_piel'      => ['nullable', 'string', 'max:50'],
            'color_ojos'      => ['nullable', 'string', 'max:50'],
            'anio_nacimiento' => ['nullable', 'string', 'max:20', 'regex:/^[\w\s\.\-]+$/'],
            'genero'          => ['nullable', 'string', 'in:masculino,femenino,hermafrodita,sin género,desconocido'],
        ];
    }

    public function messages(): array
    {
        return [
            'planeta_id.exists' => 'El planeta especificado no existe.',
            'especie_id.exists' => 'La especie especificada no existe.',
        ];
    }
}
