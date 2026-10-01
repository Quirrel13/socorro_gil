<?php

namespace App\Repositories;

use App\Models\Pix;

class PixRepository extends BaseRepository
{
    public function __construct(protected Pix $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }

    public function listarPorConta(int $contaId, ?string $inicio = null, ?string $fim = null)
    {
        return $this->getModel()->newQuery()
            ->where(function ($query) use ($contaId) {
                $query->where('conta_origem_id', $contaId)
                    ->orWhere('conta_destino_id', $contaId);
            })
            ->when($inicio, fn ($query) => $query->whereDate('created_at', '>=', $inicio))
            ->when($fim, fn ($query) => $query->whereDate('created_at', '<=', $fim))
            ->with(['contaOrigem.cliente', 'contaDestino.cliente'])
            ->orderByDesc('created_at')
            ->get();
    }
}