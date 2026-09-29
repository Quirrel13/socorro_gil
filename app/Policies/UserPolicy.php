<?php

namespace App\Policies;

use App\Models\User;
use App\Services\PermissionService;

class UserPolicy
{
    public function __construct(
        protected PermissionService $service
    ) {
    }

    public function view(User $user, User $model): bool
    {
        // Todo usuário pode visualizar o próprio usuário
        if ($user->id === $model->id) {
            return true;
        }

        // Gerente geral pode visualizar gerente de conta
        if (
            $this->service->isAuthorized('gerente_conta.show', $user)
            && $model->role->name === 'gerente_conta'
        ) {
            return true;
        }

        // Gerente de conta pode visualizar seus clientes
        if (
            $user->role->name === 'gerente_conta'
            && $model->role->name === 'cliente'
        ) {
            return $model->contaCliente?->gerente_id === $user->id;
        }

        return false;
    }

    public function update(User $user, User $model): bool
    {
        // Todo usuário só pode editar seus próprios dados
        return $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        // Gerente geral cria gerente de conta
        if (
            $this->service->isAuthorized('gerente_conta.create', $user)
        ) {
            return true;
        }

        // Gerente de conta cria cliente
        //
        // Aqui usamos o fato de que o gerente possui
        // a responsabilidade de manter clientes.
        return $user->role->name === 'gerente_conta';
    }

    public function delete(User $user, User $model): bool
    {
        // Gerente geral pode excluir gerente de conta
        if (
            $this->service->isAuthorized('gerente_conta.delete', $user)
            && $model->role->name === 'gerente_conta'
        ) {
            return true;
        }

        return false;
    }
}