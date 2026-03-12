<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'          => ['required', 'string', 'max:100'],
            'modelo'          => ['nullable', 'string', 'max:150'],
            'fabricante'      => ['nullable', 'string', 'max:200'],
            'clase_vehiculo'   => ['nullable', 'string', 'max:100'],
            'longitud'         => ['nullable', 'numeric', 'min:0'],
            'costo_creditos'   => ['nullable', 'string', 'max:50'],
            'velocidad_maxima' => ['nullable', 'string', 'max:50'],
            'tripulacion'      => ['nullable', 'string', 'max:50'],
            'pasajeros'        => ['nullable', 'string', 'max:50'],
            'capacidad_carga'  => ['nullable', 'string', 'max:50'],
            'consumibles'       => ['nullable', 'string', 'max:100'],
            'pelicula_ids'     => ['nullable', 'array'],
            'pelicula_ids.*'  => ['integer', 'exists:peliculas,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'       => 'El nombre del vehículo es obligatorio.',
            'pelicula_ids.*.exists' => 'Una o más películas no existen.',
        ];
    }
}
