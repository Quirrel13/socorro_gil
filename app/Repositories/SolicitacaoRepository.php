<?php

namespace App\Repositories;

use App\Enums\StatusSolicitacao;
use App\Models\Solicitacao;
use Illuminate\Database\Eloquent\Model;

class SolicitacaoRepository extends BaseRepository
{
    public function __construct(protected Solicitacao $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }

    public function listarTodas()
    {
        return $this->getModel()->newQuery()
            ->with(['conta.cliente', 'solicitante', 'avaliador'])
            ->orderByDesc('id')
            ->get();
    }

    public function listarPorGerente(int $gerenteId)
    {
        return $this->getModel()->newQuery()
            ->where('solicitante_id', $gerenteId)
            ->with(['conta.cliente', 'solicitante', 'avaliador'])
            ->orderByDesc('id')
            ->get();
    }

    public function existePendente(int $contaId): bool
    {
        return $this->getModel()->newQuery()
            ->where('conta_id', $contaId)
            ->where('status', StatusSolicitacao::PENDENTE->value)
            ->exists();
    }

    public function buscarParaAtualizar(int|string $id): ?Model
    {
        return $this->getModel()->newQuery()
            ->lockForUpdate()
            ->find($id);
    }

    public function contarPendentes(?int $solicitanteId = null): int
    {
        return $this->getModel()->newQuery()
            ->where('status', StatusSolicitacao::PENDENTE->value)
            ->when($solicitanteId, fn ($q) => $q->where('solicitante_id', $solicitanteId))
            ->count();
    }
}