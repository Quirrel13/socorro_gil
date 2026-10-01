<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'email' => 'Informe um endereço de e-mail válido!',
            'max' => 'Este campo possui tamanho máximo de :max caracteres!',
            'min' => 'Este campo possui tamanho mínimo de :min caracteres!',
            'unique' => 'Este e-mail já está cadastrado!',
            'confirmed' => 'A confirmação da senha não confere!',
        ];
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
        ];

        if ($this->isMethod('post')) {
            $rules['email'] = 'required|string|email|max:255|unique:users,email';
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->email)) {
            $this->merge(['email' => mb_strtolower(trim($this->email))]);
        }
    }
}