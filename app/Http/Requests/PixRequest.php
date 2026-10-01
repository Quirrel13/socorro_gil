<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PixRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'valor' => 'required|numeric|decimal:0,2|min:0.01|max:99999999.99',
            'conta_destino_id' => ['required', 'integer', Rule::exists('contas', 'id')->whereNull('deleted_at')],
            'descricao' => 'nullable|string|max:255',
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

            'conta_destino_id.required' => 'A conta de destino é obrigatória!',
            'conta_destino_id.integer' => 'A conta de destino é inválida!',
            'conta_destino_id.exists' => 'A conta de destino não foi encontrada!',

            'descricao.string' => 'A descrição deve ser um texto!',
            'descricao.max' => 'A descrição deve ter no máximo :max caracteres!',
        ];
    }
}