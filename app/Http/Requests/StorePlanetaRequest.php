<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanetaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'               => ['required', 'string', 'max:100', 'unique:planetas,nombre', 'regex:/^[\w\s\-\.]+$/u'],
            'clima'                => ['nullable', 'string', 'max:100'],
            'terreno'              => ['nullable', 'string', 'max:100'],
            'diametro'             => ['nullable', 'integer', 'min:0'],
            'poblacion'            => ['nullable', 'integer', 'min:0'],
            'periodo_rotacion'     => ['nullable', 'integer', 'min:0'],
            'periodo_orbital'      => ['nullable', 'integer', 'min:0'],
            'gravedad'             => ['nullable', 'string', 'max:50'],
            'agua_superficial'     => ['nullable', 'string', 'max:10'],

            'habitantes'                    => ['nullable', 'array', 'max:50'],
            'habitantes.*.nombre'           => ['required', 'string', 'max:100'],
            'habitantes.*.altura'           => ['nullable', 'integer', 'min:0', 'max:500'],
            'habitantes.*.masa'             => ['nullable', 'integer', 'min:0', 'max:1000'],
            'habitantes.*.color_cabello'    => ['nullable', 'string', 'max:50'],
            'habitantes.*.color_piel'       => ['nullable', 'string', 'max:50'],
            'habitantes.*.color_ojos'       => ['nullable', 'string', 'max:50'],
            'habitantes.*.anio_nacimiento'  => ['nullable', 'string', 'max:20', 'regex:/^[\w\s\.\-]+$/'],
            'habitantes.*.genero'           => ['nullable', 'string', 'in:masculino,femenino,hermafrodita,sin género,desconocido'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del planeta es obligatorio.',
            'nombre.unique'   => 'Ya existe un planeta con ese nombre.',
            'nombre.regex'    => 'El nombre contiene caracteres no permitidos.',
        ];
    }
}
