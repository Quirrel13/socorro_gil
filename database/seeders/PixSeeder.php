<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pix;
use App\Models\Conta;
use App\Models\User;

class PixSeeder extends Seeder
{
    public function run(): void
    {
        $cliente1 = User::where('email', 'cliente1@ifbank.com')->first();
        $cliente2 = User::where('email', 'cliente2@ifbank.com')->first();
        $cliente3 = User::where('email', 'cliente3@ifbank.com')->first();

        $conta1 = Conta::where('cliente_id', $cliente1->id)->first();
        $conta2 = Conta::where('cliente_id', $cliente2->id)->first();
        $conta3 = Conta::where('cliente_id', $cliente3->id)->first();

        Pix::create([
            'valor' => 250.00,
            'conta_origem_id' => $conta1->id,
            'conta_destino_id' => $conta2->id,
            'descricao' => 'Pagamento de serviço',
        ]);

        Pix::create([
            'valor' => 100.00,
            'conta_origem_id' => $conta2->id,
            'conta_destino_id' => $conta1->id,
            'descricao' => 'Devolução de pagamento',
        ]);

        Pix::create([
            'valor' => 300.00,
            'conta_origem_id' => $conta2->id,
            'conta_destino_id' => $conta3->id,
            'descricao' => 'Transferência para Cliente 3',
        ]);

        Pix::create([
            'valor' => 50.00,
            'conta_origem_id' => $conta3->id,
            'conta_destino_id' => $conta1->id,
            'descricao' => 'Pagamento',
        ]);
    }
}