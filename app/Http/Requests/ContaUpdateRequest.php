<?php

namespace App\Http\Requests;

use App\Models\Conta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContaUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->email)) {
            $this->merge(['email' => mb_strtolower(trim($this->email))]);
        }
    }

    protected function clienteId(): int
    {
        $conta = $this->route('conta');

        if (!$conta instanceof Conta) {
            $conta = Conta::findOrFail($conta);
        }

        return $conta->cliente_id;
    }

    public function messages(): array
    {
        return [
            'required' => 'O preenchimento deste campo é obrigatório!',
            'string' => 'Este campo deve ser um texto!',
            'email' => 'Informe um endereço de e-mail válido!',
            'unique' => 'Este e-mail já está cadastrado!',
            'confirmed' => 'A confirmação da senha não confere!',
            'min' => 'Este campo possui tamanho mínimo de :min caracteres!',
            'max' => 'Este campo possui tamanho máximo de :max caracteres!',
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($this->clienteId()),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }
}