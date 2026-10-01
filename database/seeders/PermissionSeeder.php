<?php

namespace Database\Seeders;

use App\Enums\NomeRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $mapa = [
            NomeRole::GERENTE_GERAL->value => [
                'perfil.edit',
                'gerente_conta.index',
                'gerente_conta.create',
                'gerente_conta.edit',
                'gerente_conta.delete',
                'gerente_conta.show',
                'solicitacao.index',
                'solicitacao.aprovar',
                'solicitacao.recusar',
                'auditoria.index',
            ],
            NomeRole::GERENTE_CONTA->value => [
                'perfil.edit',
                'conta.index',
                'conta.show',
                'conta.create',
                'conta.edit',
                'conta.delete',
                'conta.bloquear',
                'conta.desbloquear',
                'solicitacao.index',
                'solicitacao.create',
                'extrato.index',
                'investimento.index',
            ],
            NomeRole::CLIENTE->value => [
                'perfil.edit',
                'extrato.index',
                'saldo.show',
                'investimento.index',
                'investimento.aplicar',
                'investimento.resgatar',
                'pix.create',
            ],
        ];

        $permissions = [];

        foreach ($mapa as $nomeRole => $recursos) {
            $roleId = DB::table('roles')->where('name', $nomeRole)->value('id');

            foreach ($recursos as $recurso) {
                $resourceId = DB::table('resources')->where('name', $recurso)->value('id');

                if (!$resourceId) {
                    throw new \RuntimeException(
                        "Resource '{$recurso}' não está cadastrado no ResourceSeeder."
                    );
                }

                $permissions[] = [
                    'role_id' => $roleId,
                    'resource_id' => $resourceId,
                ];
            }
        }

        DB::table('permissions')->insert($permissions);
    }
}