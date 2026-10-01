<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovimentacaoInvestimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'valor' => 'required|numeric|decimal:0,2|min:0.01|max:99999999.99',
        ];
    }

    public function messages(): array
    {
        return [
            'valor.required' => 'O preenchimento deste campo é obrigatório!',
            'valor.numeric' => 'Este campo deve ser numérico!',
            'valor.decimal' => 'Informe no máximo 2 casas decimais!',
            'valor.min' => 'O valor deve ser maior que zero!',
            'valor.max' => 'O valor informado é muito alto!',
        ];
    }
}