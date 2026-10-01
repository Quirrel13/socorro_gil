<?php

namespace App\Repositories;

use App\Models\MovimentacaoInvestimento;

class MovimentacaoInvestimentoRepository extends BaseRepository
{
    public function __construct(protected MovimentacaoInvestimento $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }

    public function listarPorCliente(int $clienteId)
    {
        return $this->getModel()->newQuery()
            ->whereHas('investimento.conta', function ($query) use ($clienteId) {
                $query->where('cliente_id', $clienteId);
            })
            ->with(['investimento.conta', 'investimento.tipo'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function listarPorConta(int $contaId, ?string $inicio = null, ?string $fim = null)
    {
        return $this->getModel()->newQuery()
            ->whereHas('investimento', function ($query) use ($contaId) {
                $query->where('conta_id', $contaId);
            })
            ->when($inicio, fn ($query) => $query->whereDate('created_at', '>=', $inicio))
            ->when($fim, fn ($query) => $query->whereDate('created_at', '<=', $fim))
            ->with('investimento.tipo')
            ->orderByDesc('created_at')
            ->get();
    }
}