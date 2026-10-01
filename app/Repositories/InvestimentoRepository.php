<?php

namespace App\Repositories;

use App\Models\Investimento;
use Illuminate\Database\Eloquent\Model;

class InvestimentoRepository extends BaseRepository
{
    public function __construct(protected Investimento $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }

    public function listarPorConta(int $contaId)
    {
        return $this->getModel()->newQuery()
            ->with('tipo')
            ->where('conta_id', $contaId)
            ->orderBy('tipo_investimento_id')
            ->get();
    }

    public function buscarParaAtualizar(int|string $id): ?Model
    {
        return $this->getModel()->newQuery()
            ->lockForUpdate()
            ->find($id);
    }
}