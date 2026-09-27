<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conta;
use App\Models\User;

class ContaSeeder extends Seeder
{
    public function run(): void
    {
        $gerente1 = User::where('email', 'gerente1@ifbank.com')->first();
        $gerente2 = User::where('email', 'gerente2@ifbank.com')->first();

        $cliente1 = User::where('email', 'cliente1@ifbank.com')->first();
        $cliente2 = User::where('email', 'cliente2@ifbank.com')->first();
        $cliente3 = User::where('email', 'cliente3@ifbank.com')->first();

        Conta::create([
            'saldo' => 1500.00,
            'limite' => 1000.00,
            'bloqueado' => false,
            'cliente_id' => $cliente1->id,
            'gerente_id' => $gerente1->id,
        ]);

        Conta::create([
            'saldo' => 3500.00,
            'limite' => 2000.00,
            'bloqueado' => false,
            'cliente_id' => $cliente2->id,
            'gerente_id' => $gerente1->id,
        ]);

        Conta::create([
            'saldo' => 750.00,
            'limite' => 500.00,
            'bloqueado' => false,
            'cliente_id' => $cliente3->id,
            'gerente_id' => $gerente2->id,
        ]);
    }
}