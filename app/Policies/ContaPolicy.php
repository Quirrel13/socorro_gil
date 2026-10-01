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

    protected function ehGerenteDaConta(User $user, Conta $conta): bool
    {
        return (int) $conta->gerente_id === (int) $user->id;
    }

    protected function ehClienteDaConta(User $user, Conta $conta): bool
    {
        return (int) $conta->cliente_id === (int) $user->id;
    }

    public function viewAny(User $user): bool
    {
        return $this->service->isAuthorized('conta.index', $user);
    }

    public function view(User $user, Conta $conta): bool
    {
        return $this->service->isAuthorized('conta.show', $user)
            && $this->ehGerenteDaConta($user, $conta);
    }

    public function verSaldo(User $user, Conta $conta): bool
    {
        return $this->service->isAuthorized('saldo.show', $user)
            && $this->ehClienteDaConta($user, $conta);
    }

    public function create(User $user): bool
    {
        return $this->service->isAuthorized('conta.create', $user);
    }

    public function update(User $user, Conta $conta): bool
    {
        return $this->service->isAuthorized('conta.edit', $user)
            && $this->ehGerenteDaConta($user, $conta);
    }

    public function delete(User $user, Conta $conta): bool
    {
        return $this->service->isAuthorized('conta.delete', $user)
            && $this->ehGerenteDaConta($user, $conta);
    }

    public function bloquear(User $user, Conta $conta): bool
    {
        return $this->service->isAuthorized('conta.bloquear', $user)
            && $this->ehGerenteDaConta($user, $conta);
    }

    public function desbloquear(User $user, Conta $conta): bool
    {
        return $this->service->isAuthorized('conta.desbloquear', $user)
            && $this->ehGerenteDaConta($user, $conta);
    }

    public function extrato(User $user, Conta $conta): bool
    {
        if (!$this->service->isAuthorized('extrato.index', $user)) {
            return false;
        }

        if ($this->ehGerenteDaConta($user, $conta)) {
            return true;
        }

        return $this->ehClienteDaConta($user, $conta) && !$conta->bloqueado;
    }

    // Gerente pedindo aumento de limite para uma conta sua
    public function solicitarLimite(User $user, Conta $conta): bool
    {
        return $this->service->isAuthorized('solicitacao.create', $user)
            && $this->ehGerenteDaConta($user, $conta);
    }
}