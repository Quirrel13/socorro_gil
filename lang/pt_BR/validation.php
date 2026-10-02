<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser um texto.',
    'email' => 'O campo :attribute deve ser um e-mail válido.',
    'confirmed' => 'A confirmação do campo :attribute não confere.',
    'unique' => 'Este :attribute já está em uso.',
    'current_password' => 'A senha atual está incorreta.',
    'numeric' => 'O campo :attribute deve ser numérico.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'exists' => 'O valor informado em :attribute é inválido.',
    'date' => 'O campo :attribute não é uma data válida.',
    'after_or_equal' => 'O campo :attribute deve ser uma data igual ou posterior a :date.',
    'min' => [
        'numeric' => 'O campo :attribute deve ser no mínimo :min.',
        'string' => 'O campo :attribute deve ter no mínimo :min caracteres.',
    ],
    'max' => [
        'numeric' => 'O campo :attribute deve ser no máximo :max.',
        'string' => 'O campo :attribute deve ter no máximo :max caracteres.',
    ],
    'attributes' => [
        'name' => 'nome',
        'email' => 'e-mail',
        'password' => 'senha',
        'current_password' => 'senha atual',
        'password_confirmation' => 'confirmação da senha',
        'saldo' => 'saldo',
        'limite' => 'limite',
        'valor' => 'valor',
    ],
];