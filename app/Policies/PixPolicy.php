<?php

namespace App\Policies;

use App\Models\Pix;
use App\Models\User;
use App\Services\PermissionService;

class PixPolicy
{
    public function __construct(
        protected PermissionService $service
    ) {
    }

    public function create(User $user): bool
    {
        return $this->service->isAuthorized('pix.create', $user);
    }

    public function viewAny(User $user): bool
    {
        return $this->service->isAuthorized('extrato.index', $user);
    }

    public function view(User $user, Pix $pix): bool
    {
        if (!$this->service->isAuthorized('extrato.index', $user)) {
            return false;
        }

        $contaOrigem = $pix->contaOrigem;
        $contaDestino = $pix->contaDestino;

        return $contaOrigem->cliente_id === $user->id
            || $contaOrigem->gerente_id === $user->id
            || $contaDestino->cliente_id === $user->id
            || $contaDestino->gerente_id === $user->id;
    }
}