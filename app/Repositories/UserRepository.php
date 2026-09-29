<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    protected function getModel(): mixed
    {
        return new User();
    }

    public function listarGerentesDeConta()
    {
        return $this->getModel()
            ->whereHas('role', function ($query) {
                $query->where('name', 'gerente_conta');
            })
            ->get();
    }

    public function listarClientes()
    {
        return $this->getModel()
            ->whereHas('role', function ($query) {
                $query->where('name', 'cliente');
            })
            ->get();
    }

    public function listarClientesPorGerente(int $gerenteId)
    {
        return $this->getModel()
            ->whereHas('role', function ($query) {
                $query->where('name', 'cliente');
            })
            ->whereHas('conta', function ($query) use ($gerenteId) {
                $query->where('gerente_id', $gerenteId);
            })
            ->get();
    }

    public function listarClientesComContaBloqueada()
    {
        return $this->getModel()
            ->whereHas('role', function ($query) {
                $query->where('name', 'cliente');
            })
            ->whereHas('conta', function ($query) {
                $query->where('bloqueado', true);
            })
            ->get();
    }
}