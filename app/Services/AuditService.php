<?php

namespace App\Services;

use App\Repositories\AuditRepository;

class AuditService extends BaseService
{
    public function __construct(protected AuditRepository $repository)
    {
    }

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function listarAtividadesDeGerentes(int $limite = 15)
    {
        return $this->repository->listarAtividadesDeGerentes($limite);
    }
}