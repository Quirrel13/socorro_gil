<?php

namespace App\Policies;

use App\Models\Investimento;
use App\Models\User;
use App\Services\PermissionService;

class InvestimentoPolicy
{
    public function __construct(
        protected PermissionService $service
    ) {
    }

    public function viewAny(User $user): bool
    {
        return $this->service->isAuthorized('investimento.index', $user);
    }

    public function view(User $user, Investimento $investimento): bool
    {
        return $this->service->isAuthorized('investimento.index', $user)
            && (
                (int) $investimento->conta->cliente_id === (int) $user->id ||
                (int) $investimento->conta->gerente_id === (int) $user->id
            );
    }

    public function aplicar(User $user, Investimento $investimento): bool
    {
        return $this->service->isAuthorized('investimento.aplicar', $user)
            && (int) $investimento->conta->cliente_id === (int) $user->id;
    }

    public function resgatar(User $user, Investimento $investimento): bool
    {
        return $this->service->isAuthorized('investimento.resgatar', $user)
            && $investimento->conta->cliente_id === $user->id;
    }
}