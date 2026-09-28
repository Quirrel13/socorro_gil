<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PixRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'valor' => 'required|numeric|min:0.01',
            'conta_origem_id' => 'required|integer|exists:contas,id',
            'conta_destino_id' => 'required|integer|exists:contas,id',
            'descricao' => 'nullable|string',
        ];
    }
}
