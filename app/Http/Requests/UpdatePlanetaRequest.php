<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlanetaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $planetaId = (int) $this->route('planeta');

        return [
            'nombre'           => ['sometimes', 'string', 'max:100', Rule::unique('planetas', 'nombre')->ignore($planetaId), 'regex:/^[\w\s\-\.]+$/u'],
            'clima'            => ['nullable', 'string', 'max:100'],
            'terreno'          => ['nullable', 'string', 'max:100'],
            'diametro'         => ['nullable', 'integer', 'min:0'],
            'poblacion'        => ['nullable', 'integer', 'min:0'],
            'periodo_rotacion' => ['nullable', 'integer', 'min:0'],
            'periodo_orbital'  => ['nullable', 'integer', 'min:0'],
            'gravedad'         => ['nullable', 'string', 'max:50'],
            'agua_superficial' => ['nullable', 'string', 'max:10'],
        ];
    }
}
