<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            ['name' => 'perfil.edit'],

            // GERENTE DE CONTA
            ['name' => 'gerente_conta.index'],
            ['name' => 'gerente_conta.create'],
            ['name' => 'gerente_conta.edit'],
            ['name' => 'gerente_conta.delete'],
            ['name' => 'gerente_conta.show'],

            // CONTAS
            ['name' => 'conta.index'],
            ['name' => 'conta.show'],
            ['name' => 'conta.create'],
            ['name' => 'conta.edit'],
            ['name' => 'conta.delete'],
            ['name' => 'conta.bloquear'],
            ['name' => 'conta.desbloquear'],

            // SOLICITAÇÕES
            ['name' => 'solicitacao.index'],
            ['name' => 'solicitacao.create'],
            ['name' => 'solicitacao.aprovar'],
            ['name' => 'solicitacao.recusar'],

            // EXTRATO
            ['name' => 'extrato.index'],

            // SALDO
            ['name' => 'saldo.show'],

            // INVESTIMENTOS
            ['name' => 'investimento.index'],
            ['name' => 'investimento.aplicar'],
            ['name' => 'investimento.resgatar'],

            // PIX
            ['name' => 'pix.create'],

            // AUDITORIA
            ['name' => 'auditoria.index'],
        ];

        DB::table('resources')->insert($data);
    }
}