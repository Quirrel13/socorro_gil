<?php

namespace App\Policies;

use App\Models\Conta;
use App\Models\User;
use App\Services\PermissionService;

class ContaPolicy
{
    public function __construct(
        protected PermissionService $service
    ) {
    }

    public function viewAny(User $user): bool
    {
        return $this->service->isAuthorized('conta.index', $user);
    }

    public function create(User $user): bool
    {
        return $this->service->isAuthorized('conta.create', $user);
    }

    public function update(User $user, Conta $conta): bool
    {
        return $this->service->isAuthorized('conta.edit', $user)
            && $conta->gerente_id === $user->id;
    }

    public function delete(User $user, Conta $conta): bool
    {
        return $this->service->isAuthorized('conta.delete', $user)
            && $conta->gerente_id === $user->id;
    }

    public function bloquear(User $user, Conta $conta): bool
    {
        return $this->service->isAuthorized('conta.bloquear', $user)
            && $conta->gerente_id === $user->id;
    }

    public function desbloquear(User $user, Conta $conta): bool
    {
        return $this->service->isAuthorized('conta.desbloquear', $user)
            && $conta->gerente_id === $user->id;
    }
}