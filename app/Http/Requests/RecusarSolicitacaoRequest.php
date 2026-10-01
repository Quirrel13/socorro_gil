<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecusarSolicitacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => 'O preenchimento deste campo é obrigatório!',
            'string' => 'Este campo deve ser um texto!',
            'max' => 'Este campo possui tamanho máximo de :max caracteres!',
        ];
    }

    public function rules(): array
    {
        return [
            'motivo_recusa' => 'required|string|max:255',
        ];
    }
}