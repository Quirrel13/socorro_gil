<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SolicitacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            "required" => "O preenchimento deste campo é obrigatório!",
            "integer" => "Este campo deve ser um número inteiro!",
            "numeric" => "Este campo deve ser numérico!",
            "min" => "O valor mínimo permitido é :min!",
            "exists" => "O registro informado não existe!",
        ];
    }

    public function rules(): array
    {
        return [
            'conta_id' => 'required|integer|exists:contas,id',
            'limite' => 'required|numeric|min:0.01',
        ];
    }
}