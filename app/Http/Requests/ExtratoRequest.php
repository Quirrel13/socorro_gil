<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExtratoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'date' => 'Informe uma data válida!',
            'after_or_equal' => 'A data final não pode ser anterior à data inicial!',
        ];
    }

    public function rules(): array
    {
        return [
            'data_inicial' => 'nullable|date',
            'data_final' => 'nullable|date|after_or_equal:data_inicial',
        ];
    }
}