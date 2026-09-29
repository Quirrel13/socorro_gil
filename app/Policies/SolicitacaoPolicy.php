<?php

namespace App\Policies;

use App\Models\Solicitacao;
use App\Models\User;
use App\Services\PermissionService;

class SolicitacaoPolicy
{
    public function __construct(
        protected PermissionService $service
    ) {
    }

    public function viewAny(User $user): bool
    {
        return $this->service->isAuthorized('solicitacao.index', $user);
    }

    public function create(User $user): bool
    {
        return $this->service->isAuthorized('solicitacao.create', $user);
    }

    public function aprovar(User $user, Solicitacao $solicitacao): bool
    {
        return $this->service->isAuthorized('solicitacao.aprovar', $user);
    }

    public function recusar(User $user, Solicitacao $solicitacao): bool
    {
        return $this->service->isAuthorized('solicitacao.recusar', $user);
    }
}