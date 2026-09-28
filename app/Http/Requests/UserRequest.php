<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
            "required" => "O preenchimento deste campo é obrigatório!",
            "string" => "Este campo deve ser um texto!",
            "email" => "Informe um endereço de e-mail válido!",
            "max" => "Este campo possui tamanho máximo de :max caracteres!",
            "min" => "Este campo possui tamanho mínimo de :min caracteres!",
            "unique" => "Este e-mail já está cadastrado!",
            "confirmed" => "A confirmação da senha não confere!",
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}