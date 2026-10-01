<?php

namespace App\Policies;

use App\Models\User;
use App\Services\PermissionService;

class AuditPolicy
{
    public function __construct(
        protected PermissionService $service
    ) {
    }

    public function viewAny(User $user): bool
    {
        return $this->service->isAuthorized('auditoria.index', $user);
    }
}