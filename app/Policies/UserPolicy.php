<?php

namespace App\Policies;

use App\Enums\NomeRole;
use App\Models\User;
use App\Services\PermissionService;

class UserPolicy
{
    public function __construct(
        protected PermissionService $service
    ) {
    }

    public function viewAny(User $user): bool
    {
        return $this->service->isAuthorized('gerente_conta.index', $user);
    }

    public function view(User $user, User $model): bool
    {
        if ((int) $user->id === (int) $model->id) {
            return true;
        }

        return $this->service->isAuthorized('gerente_conta.show', $user)
            && $model->temRole(NomeRole::GERENTE_CONTA);
    }

    public function create(User $user): bool
    {
        return $this->service->isAuthorized('gerente_conta.create', $user);
    }

    public function update(User $user, User $model): bool
    {
        return $this->service->isAuthorized('gerente_conta.edit', $user)
            && $model->temRole(NomeRole::GERENTE_CONTA);
    }

    public function delete(User $user, User $model): bool
    {
        return $this->service->isAuthorized('gerente_conta.delete', $user)
            && $model->temRole(NomeRole::GERENTE_CONTA);
    }
}