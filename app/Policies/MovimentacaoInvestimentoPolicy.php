<?php

namespace App\Policies;

use App\Models\MovimentacaoInvestimento;
use App\Models\User;
use App\Services\PermissionService;

class MovimentacaoInvestimentoPolicy
{
    public function __construct(
        protected PermissionService $service
    ) {
    }

    public function viewAny(User $user): bool
    {
        return $this->service->isAuthorized('extrato.index', $user);
    }

    public function view(User $user, MovimentacaoInvestimento $movimentacao): bool
    {
        if (!$this->service->isAuthorized('extrato.index', $user)) {
            return false;
        }

        $conta = $movimentacao->investimento->conta;

        return  (int) $conta->cliente_id === (int) $user->id
            || (int) $conta->gerente_id === (int) $user->id;
    }
}