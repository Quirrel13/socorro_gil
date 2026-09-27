<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $gerenteGeral = Role::where('name', 'gerente_geral')->first();
        $gerenteConta = Role::where('name', 'gerente_conta')->first();
        $cliente = Role::where('name', 'cliente')->first();

        User::create([
            'name' => 'Gerente Geral',
            'email' => 'gerente.geral@ifbank.com',
            'password' => Hash::make('12345678'),
            'role_id' => $gerenteGeral->id,
        ]);

        User::create([
            'name' => 'Gerente de Contas 1',
            'email' => 'gerente1@ifbank.com',
            'password' => Hash::make('12345678'),
            'role_id' => $gerenteConta->id,
        ]);

        User::create([
            'name' => 'Gerente de Contas 2',
            'email' => 'gerente2@ifbank.com',
            'password' => Hash::make('12345678'),
            'role_id' => $gerenteConta->id,
        ]);

        User::create([
            'name' => 'Cliente 1',
            'email' => 'cliente1@ifbank.com',
            'password' => Hash::make('12345678'),
            'role_id' => $cliente->id,
        ]);

        User::create([
            'name' => 'Cliente 2',
            'email' => 'cliente2@ifbank.com',
            'password' => Hash::make('12345678'),
            'role_id' => $cliente->id,
        ]);

        User::create([
            'name' => 'Cliente 3',
            'email' => 'cliente3@ifbank.com',
            'password' => Hash::make('12345678'),
            'role_id' => $cliente->id,
        ]);
    }
}