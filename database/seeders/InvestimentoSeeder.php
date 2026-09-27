<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Investimento;
use App\Models\Conta;
use App\Models\TipoInvestimento;
use App\Models\User;

class InvestimentoSeeder extends Seeder
{
    public function run(): void
    {
        $cliente1 = User::where('email', 'cliente1@ifbank.com')->first();
        $cliente2 = User::where('email', 'cliente2@ifbank.com')->first();
        $cliente3 = User::where('email', 'cliente3@ifbank.com')->first();

        $conta1 = Conta::where('cliente_id', $cliente1->id)->first();
        $conta2 = Conta::where('cliente_id', $cliente2->id)->first();
        $conta3 = Conta::where('cliente_id', $cliente3->id)->first();

        $cdb = TipoInvestimento::where('nome', 'CDB')->first();
        $poupanca = TipoInvestimento::where('nome', 'Poupança')->first();
        $cdi = TipoInvestimento::where('nome', 'CDI')->first();

        Investimento::create([
            'valor' => 500.00,
            'conta_id' => $conta1->id,
            'tipo_investimento_id' => $cdb->id,
        ]);

        Investimento::create([
            'valor' => 1000.00,
            'conta_id' => $conta1->id,
            'tipo_investimento_id' => $poupanca->id,
        ]);

        Investimento::create([
            'valor' => 750.00,
            'conta_id' => $conta1->id,
            'tipo_investimento_id' => $cdi->id,
        ]);

        Investimento::create([
            'valor' => 1500.00,
            'conta_id' => $conta2->id,
            'tipo_investimento_id' => $cdb->id,
        ]);

        Investimento::create([
            'valor' => 800.00,
            'conta_id' => $conta2->id,
            'tipo_investimento_id' => $poupanca->id,
        ]);

        Investimento::create([
            'valor' => 300.00,
            'conta_id' => $conta3->id,
            'tipo_investimento_id' => $cdi->id,
        ]);
    }
}