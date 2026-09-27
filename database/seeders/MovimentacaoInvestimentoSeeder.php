<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Investimento;
use App\Models\Conta;
use App\Models\User;
use App\Models\TipoInvestimento;
use App\Models\MovimentacaoInvestimento;

class MovimentacaoInvestimentoSeeder extends Seeder
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

        $investimentoCdbConta1 = Investimento::where('conta_id', $conta1->id)
            ->where('tipo_investimento_id', $cdb->id)
            ->first();

        $investimentoPoupancaConta1 = Investimento::where('conta_id', $conta1->id)
            ->where('tipo_investimento_id', $poupanca->id)
            ->first();

        $investimentoCdiConta1 = Investimento::where('conta_id', $conta1->id)
            ->where('tipo_investimento_id', $cdi->id)
            ->first();

        $investimentoCdbConta2 = Investimento::where('conta_id', $conta2->id)
            ->where('tipo_investimento_id', $cdb->id)
            ->first();

        $investimentoPoupancaConta2 = Investimento::where('conta_id', $conta2->id)
            ->where('tipo_investimento_id', $poupanca->id)
            ->first();

        $investimentoCdiConta3 = Investimento::where('conta_id', $conta3->id)
            ->where('tipo_investimento_id', $cdi->id)
            ->first();

        MovimentacaoInvestimento::create([
            'investimento_id' => $investimentoCdbConta1->id,
            'valor' => 500.00,
        ]);

        MovimentacaoInvestimento::create([
            'investimento_id' => $investimentoCdbConta1->id,
            'valor' => 50.00,
        ]);

        MovimentacaoInvestimento::create([
            'investimento_id' => $investimentoPoupancaConta1->id,
            'valor' => 1000.00,
        ]);

        MovimentacaoInvestimento::create([
            'investimento_id' => $investimentoPoupancaConta1->id,
            'valor' => 25.00,
        ]);

        MovimentacaoInvestimento::create([
            'investimento_id' => $investimentoCdiConta1->id,
            'valor' => 750.00,
        ]);

        MovimentacaoInvestimento::create([
            'investimento_id' => $investimentoCdbConta2->id,
            'valor' => 1500.00,
        ]);

        MovimentacaoInvestimento::create([
            'investimento_id' => $investimentoPoupancaConta2->id,
            'valor' => 800.00,
        ]);

        MovimentacaoInvestimento::create([
            'investimento_id' => $investimentoCdiConta3->id,
            'valor' => 300.00,
        ]);
    }
}