<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $gerenteGeral = DB::table('roles')
            ->where('name', 'gerente_geral')
            ->first();

        $gerenteConta = DB::table('roles')
            ->where('name', 'gerente_conta')
            ->first();

        $cliente = DB::table('roles')
            ->where('name', 'cliente')
            ->first();

        $permissions = [
            // GERENTE GERAL
            [
                'role_id' => $gerenteGeral->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'perfil.edit')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteGeral->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'gerente_conta.index')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteGeral->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'gerente_conta.create')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteGeral->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'gerente_conta.edit')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteGeral->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'gerente_conta.delete')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteGeral->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'gerente_conta.show')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteGeral->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'solicitacao.index')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteGeral->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'solicitacao.aprovar')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteGeral->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'solicitacao.recusar')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteGeral->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'auditoria.index')
                    ->value('id'),
            ],

            // GERENTE DE CONTA
            [
                'role_id' => $gerenteConta->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'perfil.edit')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteConta->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'conta.index')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteConta->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'conta.create')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteConta->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'conta.edit')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteConta->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'conta.delete')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteConta->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'conta.bloquear')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteConta->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'conta.desbloquear')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteConta->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'solicitacao.create')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteConta->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'extrato.index')
                    ->value('id'),
            ],
            [
                'role_id' => $gerenteConta->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'investimento.index')
                    ->value('id'),
            ],

            // CLIENTE
            [
                'role_id' => $cliente->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'perfil.edit')
                    ->value('id'),
            ],
            [
                'role_id' => $cliente->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'extrato.index')
                    ->value('id'),
            ],
            [
                'role_id' => $cliente->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'saldo.show')
                    ->value('id'),
            ],
            [
                'role_id' => $cliente->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'investimento.index')
                    ->value('id'),
            ],
            [
                'role_id' => $cliente->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'investimento.aplicar')
                    ->value('id'),
            ],
            [
                'role_id' => $cliente->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'investimento.resgatar')
                    ->value('id'),
            ],
            [
                'role_id' => $cliente->id,
                'resource_id' => DB::table('resources')
                    ->where('name', 'pix.create')
                    ->value('id'),
            ],
        ];

        DB::table('permissions')->insert($permissions);
    }
}