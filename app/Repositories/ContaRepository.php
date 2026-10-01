<?php

namespace App\Repositories;

use App\Models\Conta;
use Illuminate\Database\Eloquent\Model;

class ContaRepository extends BaseRepository
{
    public function __construct(protected Conta $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }

    public function listarPorGerente(int $gerenteId)
    {
        return $this->getModel()->newQuery()
            ->with('cliente')              // evita N+1 na listagem
            ->where('gerente_id', $gerenteId)
            ->orderBy('id')
            ->get();
    }

    public function buscarPorCliente(int $clienteId, array $arrWith = []): ?Model
    {
        return $this->getModel()->newQuery()
            ->with($arrWith)
            ->where('cliente_id', $clienteId)
            ->first();
    }

    public function buscarParaAtualizar(int|string $id): ?Model
    {
        return $this->getModel()->newQuery()
            ->lockForUpdate()
            ->find($id);
    }
}