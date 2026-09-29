<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService extends BaseService
{
    public function __construct(protected UserRepository $repository)
    {
    }

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function listarGerentesDeConta()
    {
        return $this->repository->listarGerentesDeConta();
    }

    public function listarClientes()
    {
        return $this->repository->listarClientes();
    }

    public function listarClientesPorGerente(int $gerenteId)
    {
        return $this->repository->listarClientesPorGerente($gerenteId);
    }

    public function listarClientesComContaBloqueada()
    {
        return $this->repository->listarClientesComContaBloqueada();
    }
}