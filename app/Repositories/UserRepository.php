<?php

namespace App\Repositories;

use App\Enums\NomeRole;
use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(protected User $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }

    public function listarGerentesDeConta()
    {
        return $this->getModel()->newQuery()
            ->whereHas('role', fn ($q) => $q->where('name', NomeRole::GERENTE_CONTA->value))
            ->orderBy('name')
            ->get();
    }

    public function listarClientes()
    {
        return $this->getModel()->newQuery()
            ->whereHas('role', fn ($q) => $q->where('name', NomeRole::CLIENTE->value))
            ->with('contaCliente')
            ->orderBy('name')
            ->get();
    }

    public function listarClientesPorGerente(int $gerenteId)
    {
        return $this->getModel()->newQuery()
            ->whereHas('role', fn ($q) => $q->where('name', NomeRole::CLIENTE->value))
            ->whereHas('contaCliente', fn ($q) => $q->where('gerente_id', $gerenteId))
            ->with('contaCliente')
            ->orderBy('name')
            ->get();
    }

    public function listarClientesComContaBloqueada()
    {
        return $this->getModel()->newQuery()
            ->whereHas('role', fn ($q) => $q->where('name', NomeRole::CLIENTE->value))
            ->whereHas('contaCliente', fn ($q) => $q->where('bloqueado', true))
            ->with('contaCliente')
            ->orderBy('name')
            ->get();
    }

    public function contarContasDoGerente(int $gerenteId): int
    {
        return $this->getModel()->newQuery()
            ->findOrFail($gerenteId)
            ->contaGerente()
            ->count();
    }
}